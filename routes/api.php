<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
|
*/

/**
 * Apis User Auth
 * @login
 * @register
 * @auth -> Middleware auth:api
 */
Route::group(['prefix' => 'user'], function () {
    Route::post('/login', 'App\Http\Controllers\Api\AuthController@login');
    Route::post('/register', 'App\Http\Controllers\Api\AuthController@register');
    Route::post('/updateBio','App\Http\Controllers\Api\AuthController@updateBio');
    Route::post('/updateAddress','App\Http\Controllers\Api\AuthController@updateAddress');
    Route::post('/updateProfile','App\Http\Controllers\Api\AuthController@updateProfile');
    Route::post('/updateAvatar','App\Http\Controllers\Api\AuthController@updateAvatar');
    Route::group(['middleware' => ['auth:api']], function () {
        Route::get('/auth', 'App\Http\Controllers\Api\AuthController@auth');
    });
});


/**
 * Coins Controller
 * @reciveCoins
 */
Route::group(['prefix' => 'coins'], function () {
    Route::post('/reciveCoins','App\Http\Controllers\Api\CoinsController@reciveCoins');
});

/**
 * Main Controller
 * @index => Fetch Indexes
 */

Route::group(['prefix' => 'main'], function () {
    Route::post('/index','App\Http\Controllers\Api\MainController@index');
    Route::post('/notification_token/store','App\Http\Controllers\Api\MainController@StoreToken');
});


/**
 * 
 * Social Controller
 * @add post
 */

Route::group(['prefix' => 'social'], function () {
    Route::post('/addpost','App\Http\Controllers\Api\SocialController@AddPost');

    // Get Posts
    Route::post('/index','App\Http\Controllers\Api\SocialController@index');

    // Get Profile 
    Route::post('/getProfile/{id}','App\Http\Controllers\Api\SocialController@getProfile');

    // Follow UnFollow
    Route::post('/follow','App\Http\Controllers\Api\SocialController@follow');
    Route::post('/unfollow','App\Http\Controllers\Api\SocialController@unFollow');

    // Like UnLIKE
    Route::post('/like','App\Http\Controllers\Api\SocialController@Like');
    Route::post('/unlike','App\Http\Controllers\Api\SocialController@UnLike');

    // Comment
    Route::post('/comment','App\Http\Controllers\Api\SocialController@comment');
    
    // Search 
    Route::post('search','App\Http\Controllers\Api\SocialController@search');
});


// Order Group 
Route::group(['prefix' => 'order'], function () {
    Route::post('store','App\Http\Controllers\Api\OrderController@store');
});


Route::get('/user_seeder','App\Http\Controllers\Api\AuthController@seeder');

Route::get('/social/test','App\Http\Controllers\Api\SocialController@test');