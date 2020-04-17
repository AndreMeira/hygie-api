<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::post('/register', 'UserController@register');

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:api')->get('/', 'HomeController@index');
Route::middleware('auth:api')->get('/me', 'UserController@me');
Route::middleware('auth:api')->get('/calories-recommandation', 'UserController@getCaloriesRecommandations');
Route::middleware('auth:api')->post('/update-body-params', 'UserController@updateBodyParams');
Route::middleware('auth:api')->post('/update-body-fat-params', 'UserController@updateBodyFat');
Route::middleware('auth:api')->post('/logout', 'UserController@logout');

Route::middleware('auth:api')->get('/daily-data', 'DailyDataController@getDailyData');
Route::middleware('auth:api')->post('/daily-data', 'DailyDataController@createDailyData');
Route::middleware('auth:api')->put('/daily-data-day/{id}', 'DailyDataController@updateDailyDataDay');
