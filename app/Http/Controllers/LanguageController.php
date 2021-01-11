<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class LanguageController extends Controller
{
    /**
     *  言語切り替え処理 
     */
    public function switchLang($lang)
    {
        Session::put('language',$lang);
        if (array_key_exists($lang, Config::get('languages'))) {
            Session::put('applocale', $lang);
        }
        return Redirect::back();
    }
    // public function language(Request $request, $language)
    // {
    //     // セッションに言語コードをセット
    //     Session::put('language',$language);
    //     // ダミーの戻り値
    //     return ['result'=>'OK'];
    // }
}
