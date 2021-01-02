<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes(['verify' => true]);

Route::get('/home', 'HomeController@index')->middleware('verified');

Route::group(['middleware' => ['auth']], function () {
    //ここに認証必要なページのルーティングを書く
});

Route::middleware('verified')->group(function() {
    // 本登録ユーザーだけ表示できるページ
    Route::get('/home', 'HomeController@index')->name('home');
    Route::post('/marker', 'HomeController@marker');

    Route::get('/discover', 'DiscoveryController@index');
    Route::post('/discover/store', 'DiscoveryController@store');

    Route::get('/discover/detail/{uuid}', 'DiscoveryController@detail');
    Route::post('/discover/comment/{uuid}', 'DiscoveryController@comment');

});