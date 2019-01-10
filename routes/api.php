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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group([
    'prefix' => 'auth'
], function(){
    Route::post('login', 'ApiController@login');

    Route::group([
        'middleware' => 'auth:api'
    ], function() {
        Route::get('logout', 'ApiController@logout');
        Route::get('check', 'ApiController@checkAuth');
    });
});

Route::group([
    'middleware' => 'auth:api'
], function() {
    Route::get('dashboard', 'ApiController@dashboard');
    Route::get('session/{id}', 'ApiController@getSession');
    Route::get('session/{id}/complete', 'ApiController@sessionComplete');
    Route::get('dashboard/check', 'ApiController@sessionCheck');
});