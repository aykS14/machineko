<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RulesController extends Controller
{
    //ガイド表示
    public function index(){
        return view('rules/index');
    }
    public function policy(){
        return view('rules/privacypolicy');
    }
}
