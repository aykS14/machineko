<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Discovery;
use App\Comment;
use App\Image;
use Storage;

class DiscoveryController extends Controller
{
    //投稿画面表示
    public function index(){
        $auths = Auth::user();
        return view('discover/index', compact('auths'));
    }

    //発見情報、画像アップロード
    public function store(Request $request){
        $filenames = $request->file('cats_imgs');
        if ($request->hasFile('cats_imgs')) :
                foreach ($filenames as $item):
                    // $var = date_create();
                    // $time = date_format($var, 'YmdHis');
                    // $imageName = $time . '-' . $item->getClientOriginalName();
                    // $target_path = public_path('uploads/cats_imgs/');
                    // $item->move($target_path, $imageName);
                    $path = Storage::disk('s3')->putFile('cats_imgs', $item, 'public');

                    $url = Storage::disk('s3')->url($path);

                    $keys = parse_url($url); //パース処理
                    $path = explode("/", $keys['path']); //分割処理
                    $fileName = end($path); //最後の要素を取得

                    $files[] = $fileName;
                endforeach;
                //$image = implode(",", $arr);
        else:
                $files = '';
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
        
        $discovery->save();

        // images table へ格納
        if ($files!=='') {
            $cat = Discovery::where('uuid', '=', $uuid)->first();
            $cat_id = $cat['id'];
            
            foreach ($files as $item):
                $image = new Image();

                $image->cat_id = $cat_id;
                $image->user_id = $auth_id;
                $image->filename = $item;

                $image->save();
            endforeach;
        }

        return redirect('discover/detail/'.$uuid);
    }

    //発見猫情報の詳細表示
    public function detail($uuid,Request $request){

        $auths = Auth::user();
        $cat = Discovery::where('uuid', '=', $uuid)->first();
        // $cat_imgs = explode(",", $cat['images']);
        $comments = Comment::where('cat_id', '=', $cat['id'])->latest()->get();
        // $comments = Comment::select()
        //             ->join('comments', 'users.id', '=', 'comments.user_id')
        //             ->where('cat_id', '=', $cat['id'])
        //             ->latest()
        //             ->get();
        $images = Image::where('cat_id', '=', $cat['id'])->get();
        if ($images->isEmpty()) {
            $images = array(['filename'=>'mapicon.png']);
        }
        
        return view('discover/detail', compact('auths','cat','comments','images'));
    }

    //発見猫情報へコメント投稿
    public function comment($uuid, Request $request){
        $request->validate([
            'message' => 'required',
        ]);
        if($request->new){
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
    //発見猫情報のコメント編集
    public function msgedit($uuid, $id, Request $request){
        $request->validate([
            'modmsg' => 'required',
        ]);
        if($request->edit){

            $cat = Discovery::where('uuid', '=', $uuid)->first();
            $cat_id =  $cat['id'];

            $message = $request->modmsg;
            
            $comment = Comment::where('id', '=', $id)->first();
            $comment->update([
                'message' => $message
            ]);

            return redirect('discover/detail/'.$uuid);

        }
    }
    // 猫情報の削除（自分が投稿の猫情報に限り、画像、コメントごと削除）
    public function catdelete($uuid){
        $cat = Discovery::where('uuid', '=', $uuid)->first();

        // $cat_imgs = explode(",", $cat['images']);
        $images = Image::where('cat_id', '=', $cat['id'])->get();
        $disk = Storage::disk('s3');
        foreach ($images as $image):
            // $keys = parse_url($item); //パース処理
            // $path = explode("/", $keys['path']); //分割処理
            // $last = end($path); //最後の要素を取得
            $last = $image->filename;
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

    //猫情報の修正（投稿者のみ）
    public function modify($uuid){
        $auths = Auth::user();
        $cat = Discovery::where('uuid', '=', $uuid)->first();
        return view('discover/modify', compact('auths','cat'));
    }
    public function update($uuid, Request $request){
        $pattern = $request->pattern;
        $locate = $request->locate;
        $Lat = $request->lat;
        $Lng = $request->lng;

        $filenames = $request->file('cats_imgs');
        if ($request->hasFile('cats_imgs')) :
                foreach ($filenames as $item):
                    // $var = date_create();
                    // $time = date_format($var, 'YmdHis');
                    // $imageName = $time . '-' . $item->getClientOriginalName();
                    // $target_path = public_path('uploads/cats_imgs/');
                    // $item->move($target_path, $imageName);
                    $path = Storage::disk('s3')->putFile('cats_imgs', $item, 'public');

                    $url = Storage::disk('s3')->url($path);

                    $keys = parse_url($url); //パース処理
                    $path = explode("/", $keys['path']); //分割処理
                    $fileName = end($path); //最後の要素を取得

                    $files[] = $fileName;
                endforeach;
                //$image = implode(",", $arr);
        else:
                $files = '';
        endif;

        $discovery = Discovery::where('uuid', '=', $uuid)->first();
        $discovery->update([
            'pattern' => $pattern,
            'locate' => $locate,
            'Lat' => $Lat,
            'Lng' => $Lng
        ]);
        
        // images table へ格納
        $auth_id = Auth::id();
        if ($files!=='') {
            $cat_id = $discovery['id'];
            
            foreach ($files as $item):
                $image = new Image();

                $image->cat_id = $cat_id;
                $image->user_id = $auth_id;
                $image->filename = $item;

                $image->save();
            endforeach;
        }
        return redirect('discover/detail/'.$uuid);
    }

}
