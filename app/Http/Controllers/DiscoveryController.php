<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Discovery;
use App\Comment;
use Storage;

class DiscoveryController extends Controller
{
    //投稿画面表示
    public function index(){
        $auths = Auth::user();
        return view('discover/index', compact('auths'));
    }

    //発見情報、画像アップロード
    public function store(Request $request)
    {
        $images = $request->file('cats_imgs');
        if ($request->hasFile('cats_imgs')) :
                foreach ($images as $item):
                    $var = date_create();
                    $time = date_format($var, 'YmdHis');
                    // $imageName = $time . '-' . $item->getClientOriginalName();
                    // $target_path = public_path('uploads/cats_imgs/');
                    // $item->move($target_path, $imageName);
                    $path = Storage::disk('s3')->putFile('cats_imgs', $item, 'public');
                    
                    // $path = Storage::disk('s3')->put($targetFile, fopen($sourceFile, 'r+'));
                    //$path = Storage::disk('s3')->put('cats_imgs', fopen($item, 'r+'));

                    $imageName = Storage::disk('s3')->url($path);

                    $arr[] = $imageName;
                endforeach;
                $image = implode(",", $arr);
        else:
                $image = '';
        endif;
        
        $auth_id = Auth::id();
        $uuid = Str::uuid();

        $discovery = new Discovery();
        $discovery->uuid = $uuid;
        $discovery->user_id = $auth_id;
        $discovery->pattern = $request->pattern;
        $discovery->locate = $request->locate;
        $discovery->lat = $request->lat;
        $discovery->lng = $request->lng;
        
        $discovery->images = $image;

        $discovery->save();
        
        return redirect('discover/detail/'.$uuid);
    }

    //発見猫情報の詳細表示
    public function detail($uuid,Request $request){

        $auths = Auth::user();
        $cat = Discovery::where('uuid', '=', $uuid)->first();
        $cat_imgs = explode(",", $cat['images']);
        $comments = Comment::where('cat_id', '=', $cat['id'])->latest()->get();
        return view('discover/detail', compact('auths','cat','comments','cat_imgs'));
    }

    //発見猫情報へコメント投稿
    public function comment($uuid,Request $request){
        $auth_id = Auth::id();

        $cat = Discovery::where('uuid', '=', $uuid)->first();
        $cat_id =  $cat['id'];

        $message = $request->message;

        Comment::create([
            'cat_id' => $cat_id,
            'user_id' => $auth_id,
            'message' => $message
        ]);
        
        return redirect('discover/detail/'.$uuid);
    }
}
