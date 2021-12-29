<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Test Related Routes
|--------------------------------------------------------------------------
*/


Route::group(['prefix' => 'tests/'], function () {
    Route::get('index', 'Test\TestController@index');
    Route::get('create', 'Test\TestController@create');
    Route::post('store', 'Test\TestController@store');
    Route::get('{id}/edit', 'Test\TestController@edit');
    Route::post('{id}/update', 'Test\TestController@update');
    Route::delete('{id}', 'Test\TestController@delete');
    Route::get('{id}', 'Test\TestController@show');
    Route::get('{id}/inviteUsers', 'Test\TestController@inviteUsers');
    Route::post('{id}/storeInvitations', 'Test\TestController@storeInvitations');
    Route::post('{id}/delete', 'Test\TestController@destroy');
});

Route::group(['prefix' => 'ajax/'], function () {
    Route::get('tests/getTests', 'Test\AjaxController@getTestsDataTable');
    Route::get('tests/{id}/getTestQuestions', 'Test\AjaxController@getTestQuestions');
    Route::get('tests/{id}/getModal', 'Test\AjaxController@getModal');
});

