<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Discovery;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $discoveries = Discovery::get();
        return view('home', compact('discoveries'));
    }
}
