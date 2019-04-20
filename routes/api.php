<?php

use Illuminate\Http\Request;

/**
 * API Routes
 * 
 * All routes used by the API are registered here
 */

Route::group(
    [
        'prefix' => 'auth'
    ], function () {
        Route::post('login', 'ApiController@login');
        Route::group(
            [
                'middleware' => 'auth:api'
            ], function () {
                Route::get('logout', 'ApiController@logout');
                Route::get('check', 'ApiController@checkAuth');
            }
        );
    }
);

Route::group(
    [
        'middleware' => 'auth:api'
    ], function () {
        Route::get('dashboard/', 'ApiController@dashboard');
        Route::get('sessions/get/{count}', 'ApiController@sessions');
        Route::get('events/', 'ApiController@events');
        Route::get('session/{id}', 'ApiController@getSession');
        Route::get('session/{id}/complete', 'ApiController@sessionComplete');
        Route::get('sessions/check', 'ApiController@sessionCheck');
    }
);

Route::post('/test/dummydata', 'TestDataController@create');
