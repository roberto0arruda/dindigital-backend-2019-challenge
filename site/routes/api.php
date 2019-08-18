<?php

use Illuminate\Http\Request;

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

Route::post('auth/login', 'AuthController@login');
Route::post('auth/refresh', 'AuthController@refresh');
Route::post('auth/logout', 'AuthController@logout');
Route::group([
    'middleware' => 'jwt.auth',
], function () {
    Route::post('auth/me', 'AuthController@me');

    Route::apiResource('crud/products', 'ProductApiController');
});
