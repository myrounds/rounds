<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('accounts/create', 'AccountController@create');
Route::post('accounts/login', 'AccountController@login');
Route::post('members/login', 'MemberController@login');

Route::group(['middleware' => ['api', 'multiauth:account']], function() {

    Route::get('accounts/find/{id}', 'AccountController@find');
    Route::put('accounts/update/{id}', 'AccountController@update');
    Route::delete('accounts/delete/{id}', 'AccountController@delete');

    Route::get('members/search', 'MemberController@search');
    Route::post('members/create', 'MemberController@create');
    Route::delete('members/delete/{id}', 'MemberController@delete');

    Route::post('tasks/create', 'TaskController@create');
    Route::delete('tasks/delete/{id}', 'TaskController@delete');

    Route::post('items/create', 'ItemController@create');
    Route::delete('items/delete/{id}', 'ItemController@delete');
});

Route::group(['middleware' => ['api', 'multiauth:member,account']], function() {

    Route::get('members/find/{id}', 'MemberController@find');
    Route::put('members/update/{id}', 'MemberController@update');

    Route::get('tasks/search', 'TaskController@search');
    Route::get('tasks/find/{id}', 'TaskController@find');
    Route::put('tasks/update/{id}', 'TaskController@update');

    Route::get('items/find/{id}', 'ItemController@find');
    Route::put('items/update/{id}', 'ItemController@update');
});
