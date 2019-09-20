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


/**
 * Login and register APIs
 */
Route::post('register', 'Api\Auth\RegisterController@register');
Route::post('login', 'Api\Auth\LoginController@login');

/**
 *
 */
Route::get('posts/get', 'Api\Posts\GetController@getAll');


Route::group(['middleware' => 'auth:api'], function() {

    /**
     * Logout API
     */
    Route::post('logout', 'Api\Auth\LogoutController@logout');

    /**
     * Apis that are related to the Post and that requires authentication
     */

    Route::post('post/add', 'Api\Posts\AddController@add');
    Route::put('post/edit/{id}', 'Api\Posts\EditController@edit')
        ->middleware('can:update-post,id')
        ->where('id', '[0-9]+');

    Route::delete('post/delete/{id}', 'Api\Posts\DeleteController@delete')
        ->middleware('can:delete-post,id')
        ->where('id', '[0-9]+');

    /**
     * Apis that are related to the user Actions
     */
    Route::group(['middleware' => 'can:action-approve'], function () {
        Route::get('actions/get', 'Api\Actions\GetController@getAll');
        Route::get('action/get/{id}', 'Api\Actions\GetController@getDetails')->where('id', '[0-9]+');
        Route::post('action', 'Api\Actions\HandleController@handle');
    });

    Route::get('post/get/{id}', 'Api\Posts\GetController@getDetails')
        ->where('id', '[0-9]+');


});
