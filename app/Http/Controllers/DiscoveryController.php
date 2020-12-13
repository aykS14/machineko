<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Discovery;

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
                    $imageName = $time . '-' . $item->getClientOriginalName();
                    $target_path = public_path('uploads/cats_imgs/');
                    $item->move($target_path, $imageName);
                    $arr[] = $imageName;
                endforeach;
                $image = implode(",", $arr);
        else:
                $image = '';
        endif;
        
        $auth_id = Auth::id();

        $discovery = new Discovery();
        $discovery->user_id = $auth_id;
        $discovery->pattern = $request->pattern;
        $discovery->locate = $request->locate;
        
        $discovery->images = $image;

        $discovery->save();
        return redirect('/home');
    }
}
