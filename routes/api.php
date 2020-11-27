<?php

use Illuminate\Http\Request;
use \App\Http\Controllers\WeatherController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::resource('weather', WeatherController::class);
Route::delete('erase', '\App\Http\Controllers\WeatherController@erase');
Route::patch('weather', '\App\Http\Controllers\WeatherController@store');
