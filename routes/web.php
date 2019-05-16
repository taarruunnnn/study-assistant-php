<?php

use App\Http\Controllers\UserController;

/**
 * Web Routes
 * 
 * All routes accessed via the web are defined here
 */

Route::group(
    ['middleware' => ['guest']], function () {
        Route::get(
            '/', function () {
                return view('welcome');
            }
        )->name('welcome');
    }
);


Auth::routes();

Route::get('/dashboard', 'DashboardController@index')->name('dashboard');

Route::get('user/profile', 'UserController@profile')->name('user.profile');
Route::get('user/edit', 'UserController@edit')->name('user.edit');
Route::patch('user/{user}', 'UserController@update')->name('user.update');
Route::delete('user/delete/{user}', 'UserController@destroy')->name('user.delete');

Route::get('users/universities', 'TypeaheadController@universities');
Route::get('users/modules', 'TypeaheadController@modules');

Route::group(
    [
        'prefix' => 'schedules'
    ], function () {
        Route::get('/', 'ScheduleController@show')->name('schedules.show');
        Route::get('sessions', 'ScheduleController@sessions')->name('schedules.sessions');
        Route::get('create', 'ScheduleController@create')->name('schedules.create');
        Route::post('store', 'ScheduleController@store')->name('schedules.store');
        Route::get('destroy', 'ScheduleController@destroy')->name('schedules.destroy');
        Route::post('update', 'ScheduleController@update')->name('schedules.update');
        Route::post('analyze', 'ScheduleController@analyze')->name('schedule.analyze');
        Route::post('move', 'ScheduleController@move')->name('schedules.move');

        Route::get('archive', 'ScheduleController@archive')->name('schedules.archive');
        Route::post('archive/update', 'ScheduleController@archiveUpdate')->name('schedules.archive.update');
    }
);

Route::post('/events/store', 'EventController@store')->name('events.store');
Route::post('/events/update', 'EventController@update')->name('events.update');
Route::post('/events/destroy', 'EventController@destroy')->name('events.destroy');

Route::get('/session', 'SessionController@show')->name('session.show');
Route::get('/session/complete', 'SessionController@complete')->name('session.complete');

Route::group(
    [
        'prefix' => 'reports'
    ], function () {
        Route::get('/', 'ReportController@show')->name('reports.show');
        Route::get('view/{report}', 'ReportController@view')->name('reports.view');
        Route::get('generate', 'ReportController@generate')->name('report.generate');
        Route::post('analyze', 'ReportController@analyze')->name('report.analyze');
        Route::post('predict', 'ReportController@predict')->name('report.predict');
        Route::post('save', 'ReportController@save')->name('report.save');
        Route::get('destroy', 'ReportController@destroy')->name('report.destroy');
    }
);

Route::group(
    [
        'prefix' => 'admin',
        'middleware' => 'is_admin'
    ], function () {
        Route::get('dashboard', 'AdminController@dashboard')->name('admin.dashboard');
        Route::get('analzye', 'AdminController@analyze')->name('admin.analyze');
        Route::get('predictions', 'AdminController@predictions')->name('admin.predictions');
        Route::post('predictions/accuracy', 'AdminController@predictionAccuracy')->name('admin.predictions.accuracy');
        Route::post('predictions/save', 'AdminController@predictionSave')->name('admin.predictions.save');
        Route::get('users', 'AdminController@users')->name('admin.users');
        Route::post('users/details', 'AdminController@userDetails')->name('admin.users.details');
        Route::post('users/destroy', 'AdminController@userDelete')->name('admin.users.destroy');
        Route::get('predictions/reset', function() {
            retrainModels();
        })->name('admin.predictions.reset');
    }
);

Route::group(
    [
        'prefix' => 'help'
    ], function () {
        Route::get('/', 'HelpController@index')->name('help');
        Route::get('/faq/{faq}', 'HelpController@show')->name('help.faq');
        Route::view('/policy', 'help.policy')->name('help.policy');
    }
);