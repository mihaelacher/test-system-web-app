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
    Route::post('create', 'User\UserController@store');
    Route::get('edit/{id}', 'User\UserController@edit');
    Route::post('update/{id}', 'User\UserController@update');
    Route::get('changePassword/{id}', 'User\UserController@changePassword');
    Route::post('changePassword/{id}', 'User\UserController@storePassword');
    Route::delete('{id}', 'User\UserController@delete');
    Route::get('{id}', 'User\UserController@show');
});

Route::group(['prefix' => 'ajax/'], function () {
    Route::get('users/getUsers', 'User\AjaxController@getUsersDatatable');
});

