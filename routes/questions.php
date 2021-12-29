<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Question Related Routes
|--------------------------------------------------------------------------
*/


Route::group(['prefix' => 'questions/'], function () {
    Route::get('index', 'Question\QuestionController@index');
    Route::get('create', 'Question\QuestionController@create');
    Route::post('store', 'Question\QuestionController@store');
    Route::get('{id}/edit', 'Question\QuestionController@edit');
    Route::post('{id}/update', 'Question\QuestionController@update');
    Route::get('{id}', 'Question\QuestionController@show');
    Route::post('{id}/delete', 'Question\QuestionController@delete');
});

Route::group(['prefix' => 'ajax/'], function () {
    Route::get('questions/getQuestions', 'Question\AjaxController@getQuestionsDataTable');
});

