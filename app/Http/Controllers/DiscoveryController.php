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
        // $comments = Comment::select()
        //             ->join('comments', 'users.id', '=', 'comments.user_id')
        //             ->where('cat_id', '=', $cat['id'])
        //             ->latest()
        //             ->get();
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

    // 猫情報の削除（自分が投稿の猫情報に限り、画像、コメントごと削除）
    public function catdelete($uuid){
        $cat = Discovery::where('uuid', '=', $uuid)->first();

        $cat_imgs = explode(",", $cat['images']);
        $disk = Storage::disk('s3');
        foreach ($cat_imgs as $item):

            $keys = parse_url($item); //パース処理
            $path = explode("/", $keys['path']); //分割処理
            $last = end($path); //最後の要素を取得

            $disk->delete("/cats_imgs/$last");
        endforeach;

        $comments = Comment::where('cat_id', '=', $cat['id'])->get();
        foreach ($comments as $comment):
            $comment->delete();
        endforeach;

        $cat->delete();

        return redirect('/home');
    }

    // コメントの削除（自分が投稿の分だけ）
    public function msgdelete($uuid,$id){
        $comment = Comment::where('id', '=', $id)->first();
        $comment->delete();
        return redirect('discover/detail/'.$uuid);
    }
}
