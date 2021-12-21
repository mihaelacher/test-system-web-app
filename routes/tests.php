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
    Route::post('create', 'Test\TestController@store');
    Route::get('edit/{id}', 'Test\TestController@edit');
    Route::post('update/{id}', 'Test\TestController@update');
    Route::delete('{id}', 'Test\TestController@delete');
    Route::get('{id}', 'Test\TestController@show');
    Route::get('inviteUsers/{id}', 'Test\TestController@inviteUsers');
    Route::post('storeInvitations/{id}', 'Test\TestController@storeInvitations');
});

Route::group(['prefix' => 'ajax/'], function () {
    Route::get('tests/getTests', 'Test\AjaxController@getTestsDataTable');
    Route::get('tests/loadQuestions', 'Test\AjaxController@loadQuestions');
    Route::get('tests/getTestQuestions', 'Test\AjaxController@getTestQuestions');
});

