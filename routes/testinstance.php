<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Test instance Related Routes
|--------------------------------------------------------------------------
*/

Route::post('/testinstance/{id}/startExecution', 'TestInstance\TestInstanceController@startExecution');

