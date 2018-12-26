<?php

use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(App\Session::class, function (Faker $faker) {
    $modules = [
        'IT',
        'History',
        'Science',
        'Geography',
        'English',
        'French',
        'Economics',
        'Law',
        'Sinhala',
        'Sociology',
        'Biology',
        'Chemistry',
        'Physics',
        'Mathematics',
        'Statistics'
    ];

    
    return [
        'module' => $modules[rand(0, count($modules) - 1)],
        'date' => $faker->dateTimeBetween($startDate = '2018-10-25', $endDate = '2019-04-20', $timezone = null),
        'status' => 'completed',
        'schedule_id' => '1'
    ];
});
