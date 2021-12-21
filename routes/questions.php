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
    Route::post('create', 'Question\QuestionController@store');
    Route::get('edit/{id}', 'Question\QuestionController@edit');
    Route::post('update/{id}', 'Question\QuestionController@update');
    Route::get('{id}', 'Question\QuestionController@show');
    Route::delete('{id}', 'Question\QuestionController@delete');
});

Route::group(['prefix' => 'ajax/'], function () {
    Route::get('questions/getQuestions', 'Question\AjaxController@getQuestionsDataTable');
});

