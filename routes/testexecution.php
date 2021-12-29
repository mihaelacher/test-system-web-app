<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Test execution Related Routes
|--------------------------------------------------------------------------
*/


Route::group(['prefix' => 'testexecution/'], function() {
    Route::get('index', 'TestExecution\TestExecutionController@index');
    Route::get('{id}/show', 'TestExecution\TestExecutionController@show');
    Route::get('{id}/evaluate', 'TestExecution\TestExecutionController@evaluate');
    Route::post('{id}/evaluate', 'TestExecution\TestExecutionController@submitEvaluation');
    Route::get('{id}/execute', 'TestExecution\TestExecutionController@execute');
    Route::post('{id}/submit', 'TestExecution\TestExecutionController@submit');
});

Route::group(['prefix' => 'ajax/'], function () {
    Route::get('testexecution/getTestExecutions', 'TestExecution\AjaxController@getTestExecutionsDataTable');
    Route::post('testexecution/{id}/submitOpenQuestion', 'TestExecution\AjaxController@submitOpenQuestion');
    Route::post('testexecution/{id}/submitQuestionAnswer', 'TestExecution\AjaxController@submitQuestionAnswer');
});
