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

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/



Route::post('/login', 'LoginController@signin');
Route::post('/register', 'LoginController@register');

	 Route::group(['middleware' => 'jwt.auth'], function () {
        Route::get('user', [
            'uses' => 'LoginController@user',
        ]);
        Route::get('/casas', 'CensoController@getAll');
        Route::post('/casas', 'CensoController@save');
        Route::get('/casas/{id}', 'CensoController@getCasa');
    });


