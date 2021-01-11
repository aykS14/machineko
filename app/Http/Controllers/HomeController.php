<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Discovery;
use App\Image;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        // $this->middleware('auth');
        $this->middleware('verified');
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
    public function marker(Request $request)
    {
        $lat = $request->input('lat');
        $lng = $request->input('lng');
        
        $latmin = $request->input('swLat');
        $latmax = $request->input('neLat');

        $lngmin = $request->input('swlng');
        $lngmax = $request->input('nelng');

        $discoveries = Discovery::
            whereBetween('lat', [$latmin, $latmax])
            ->whereBetween('lng', [$lngmin, $lngmax])
            ->leftJoin('images', 'discoveries.id', '=', 'images.cat_id')
            ->get();

        return response()->json($discoveries);
    }
}
