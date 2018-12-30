<?php

use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Auth::routes();

Route::get('/dashboard', 'DashboardController@index')->name('dashboard');


Route::get('user/{user}', 'UserController@edit')->name('user.edit');
Route::patch('user/{user}', 'UserController@update')->name('user.update');
Route::delete('users/delete/{user}', 'UserController@destroy')->name('user.delete');

Route::get('users/universities', 'ListUniversities');

Route::get('/schedules', 'ScheduleController@show')->name('schedules.show');
Route::get('/schedules/create', 'ScheduleController@create')->name('schedules.create');
Route::post('/schedules/store', 'ScheduleController@store')->name('schedules.store');
Route::get('/schedules/delete', 'ScheduleController@destroy')->name('schedules.destroy');
Route::post('/schedules/update', 'ScheduleController@update')->name('schedules.update');
Route::post('/schedules/analyze', 'ScheduleController@analyze')->name('schedule.analyze');
Route::post('/schedules/move', 'ScheduleController@move')->name('schedule.move');
Route::get('/schedules/archive', 'ScheduleController@archive')->name('schedules.archive');

Route::get('/session', 'SessionController@show')->name('session.show');
Route::get('/session/complete', 'SessionController@complete')->name('session.complete');
Route::get('/session/refresh', 'SessionController@refresh')->name('session.refresh');

Route::get('/reports', 'ReportController@show')->name('reports.show');
Route::get('/reports/view/{report}', 'ReportController@view')->name('reports.view');
Route::get('/reports/generate', 'ReportController@generate')->name('report.generate');
Route::post('/reports/analyze', 'ReportController@analyze')->name('report.analyze');
Route::post('/reports/save', 'ReportController@save')->name('report.save');
