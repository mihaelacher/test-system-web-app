<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'App\Http\Controllers\Home\HomeController@home');

Route::group(['prefix' => 'auth/'], function () {
    Route::get('login', 'App\Http\Controllers\Auth\LoginController@showLoginForm');
    Route::post('login', 'App\Http\Controllers\Auth\LoginController@login');
    Route::get('logout', 'App\Http\Controllers\Auth\LoginController@logout');
});

Route::group(['prefix' => 'questions/'], function () {
    Route::get('/index','App\Http\Controllers\Question\QuestionController@index');
    Route::get('create','App\Http\Controllers\Question\QuestionController@create');
    Route::post('create','App\Http\Controllers\Question\QuestionController@store');
    Route::get('{id}','App\Http\Controllers\Question\QuestionController@show');
});

Route::group(['prefix' => 'ajax/'], function () {
    Route::get('questions/getQuestions', 'App\Http\Controllers\Question\AjaxController@getQuestionsDataTable');
});
