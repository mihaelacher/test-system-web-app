<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| User Related Routes
|--------------------------------------------------------------------------
*/


Route::group(['prefix' => 'users/'], function () {
    Route::get('index', 'User\UserController@index');
    Route::get('create', 'User\UserController@create');
    Route::post('store', 'User\UserController@store');
    Route::get('{id}/edit', 'User\UserController@edit');
    Route::post('{id}/update', 'User\UserController@update');
    Route::get('{id}/changePassword', 'User\UserController@changePassword');
    Route::post('{id}/storePassword', 'User\UserController@storePassword');
    Route::post('{id}/delete', 'User\UserController@delete');
    Route::get('{id}', 'User\UserController@show');
});

Route::group(['prefix' => 'ajax/'], function () {
    Route::get('users/getUsers', 'User\AjaxController@getUsersDatatable');
});

