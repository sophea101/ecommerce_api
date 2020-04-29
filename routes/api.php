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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('login', 'AuthController@login');
Route::post('register', 'AuthController@register');
Route::post('reset-password', 'AuthController@resetPassword');
Route::get('get-users', 'AuthController@users');
Route::group(['middleware' => 'auth:api'], function(){
    Route::post('details', 'AuthController@details');
    Route::resource('product', 'ProductController');      
    Route::get('product-fliter/{id}', 'ProductController@filterByCategory');
    Route::get('product-add/{id}', 'ProductController@favProduct');
});