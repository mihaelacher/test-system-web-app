<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Test execution Related Routes
|--------------------------------------------------------------------------
*/


Route::group(['prefix' => 'testexecution/'], function() {
    Route::get('index', 'TestExecution\TestExecutionController@index');
    Route::get('show/{id}', 'TestExecution\TestExecutionController@show');
    Route::get('evaluate/{id}', 'TestExecution\TestExecutionController@evaluate');
    Route::post('evaluate/{id}', 'TestExecution\TestExecutionController@submitEvaluation');
    Route::get('start/{id}', 'TestExecution\TestExecutionController@start');
    Route::post('submit/{id}', 'TestExecution\TestExecutionController@submit');
});

Route::group(['prefix' => 'ajax/'], function () {
    Route::get('testexecution/getTestExecutions', 'TestExecution\AjaxController@getTestExecutionsDataTable');
    Route::post('testexecution/submitOpenQuestion/{testExecutionId}', 'TestExecution\AjaxController@submitOpenQuestion');
    Route::post('testexecution/submitQuestionAnswer/{testExecutionId}', 'TestExecution\AjaxController@submitQuestionAnswer');
});
