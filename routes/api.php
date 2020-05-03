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
// TODO
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('login', 'AuthController@login');
Route::post('register', 'AuthController@register');
Route::post('reset-password', 'AuthController@resetPassword');
Route::get('get-users', 'AuthController@users');
Route::group(['middleware' => 'auth:api'], function(){
    Route::post('details', 'AuthController@details');
});
Route::resource('product', 'ProductController');      
Route::get('product-fliter/{id}', 'ProductController@filterByCategory');
Route::get('product-add/{id}', 'ProductController@favProduct');

// sophea api
Route::prefix('v1')->namespace('v1')->group(function(){    
    Route::post('login'             , 'AuthController@login');
    Route::post('register'          , 'AuthController@register');
    Route::post('reset-password'    , 'AuthController@resetPassword');
    Route::get('users'              , 'AuthController@users');
    // Route::resource('product', 'ProductController');      
    Route::get('product/{product_id}', 'ProductController@show');
    Route::get('products', 'ProductController@index');
    Route::get('product-by-cateogory/{id}', 'ProductController@filterByCategory');

    Route::get('user/{user_id}'               , 'AuthController@user');
    Route::post('add-card/{user_id}'               , 'BuyController@addCard');
    // Route::get('logout/{user_id}'             , 'AuthController@logout');

    Route::group(['middleware' => 'auth:api'], function(){
        Route::post('add-card'               , 'BuyController@addCard');
        Route::get('user'               , 'AuthController@user');
        Route::get('logout'             , 'AuthController@logout');
        Route::post('upload'            , 'AuthController@upload');
        Route::get('product-fav/{id}'   , 'ProductController@favProduct');
    });
});