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

/**
 * 言語切替
 */
Route::get('lang/{lang}', ['as'=>'lang.switch', 'uses'=>'LanguageController@switchLang']);
// Route::post('lang/{language}', 'LanguageController@language')->name('language');

Route::get('/', function () {
    return view('welcome');
});

Route::get('/rules', 'RulesController@index');
Route::get('/rules/privacypolicy', 'RulesController@policy');

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

    Route::get('/discover/modify/{id}', 'DiscoveryController@modify');
    Route::post('/discover/update/{id}', 'DiscoveryController@update');

    Route::get('/discover/delete/{uuid}', 'DiscoveryController@catdelete');

    Route::get('/discover/msgdelete/{uuid}/{id}', 'DiscoveryController@msgdelete');
    Route::post('/discover/msgedit/{uuid}/{id}', 'DiscoveryController@msgedit');

});