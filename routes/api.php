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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();

});

Route::post('register', 'Api\Auth\RegisterController@register');
Route::post('login', 'Api\Auth\LoginController@login');

Route::group(['middleware' => 'auth:api'], function() {
    Route::get('test', 'Api\TestController@test')->middleware('can:approve-post-action');
    Route::post('logout', 'Api\Auth\LogoutController@logout');

    /**
     * Apis that are related to the Post
     */
    Route::get('posts/get', 'Api\Post\GetController@get');
    Route::get('posts/get/{id}', 'Api\Post\GetController@getDetails')->where('id', '[0-9]+');
    Route::post('post/add', 'Api\Post\AddController@add');
    Route::put('post/edit/{id}', 'Api\Post\EditController@edit')->where('id', '[0-9]+');
    Route::delete('post/delete/{id}', 'Api\Post\DeleteController@delete');


});
