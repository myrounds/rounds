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

Route::get('login/external/{provider}/callback', 'Auth\LoginController@handleProviderCallback');
Route::get('login/external/{provider}/{type}', 'Auth\LoginController@redirectToProvider');

Route::get('{any}/', function() {
    return view('pwa');
})->where('any', '^(?!api).*$');