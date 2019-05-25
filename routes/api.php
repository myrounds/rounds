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
Route::post('assignees/login', 'AssigneeController@login');

Route::group(['middleware' => ['api', 'multiauth:account']], function() {

    Route::get('accounts/find/{id}', 'AccountController@find');
    Route::put('accounts/update/{id}', 'AccountController@update');
    Route::delete('accounts/delete/{id}', 'AccountController@delete');

    Route::get('assignees/search', 'AssigneeController@search');
    Route::post('assignees/create', 'AssigneeController@create');
    Route::delete('assignees/delete/{id}', 'AssigneeController@delete');

    Route::post('groups/create', 'GroupController@create');
    Route::delete('groups/delete/{id}', 'GroupController@delete');

    Route::post('tasks/create', 'TaskController@create');
    Route::delete('tasks/delete/{id}', 'TaskController@delete');
});

Route::group(['middleware' => ['api', 'multiauth:assignee,account']], function() {

    Route::get('assignees/find/{id}', 'AssigneeController@find');
    Route::put('assignees/update/{id}', 'AssigneeController@update');

    Route::get('groups/search', 'GroupController@search');
    Route::get('groups/find/{id}', 'GroupController@find');
    Route::put('groups/update/{id}', 'GroupController@update');

    Route::get('tasks/find/{id}', 'TaskController@find');
    Route::put('tasks/update/{id}', 'TaskController@update');
});
