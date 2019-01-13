<?php

use Faker\Generator as Faker;
use App\Http\Requests\StoreSchedule;

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

$factory->define(App\Schedule::class, function (Faker $faker) {
    $modules = [
        'IT',
        'History',
        'Science',
        'English',
        'French',
        'Law',
        'Sinhala',
        'Biology',
        'Chemistry',
        'Physics',
        'Mathematics',
        'Statistics'
    ];

    $start = $faker->dateTimeBetween($startDate = '2019-01-01', $endDate = '2019-06-01', $timezone = null);
    $start = $start->format("Y-m-d");
    $end = $faker->dateTimeBetween($startDate = '2019-06-30', $endDate = '2020-01-01', $timezone = null);
    $end = $end->format("Y-m-d");
    
    return [
        'test' => '1',
        'start' => $start,
        'end' => $end,
        'weekdays' => $faker->randomElement($array = array('2','4','6')),
        'weekends' => $faker->randomElement($array = array('2','4','6')),
        'module' => $faker->randomElements($array = $modules, $count = 4),
        'rating' => $faker->randomElements($array = array('1', '2', '3', '4', '5', '6', '7', '8', '9', '10'), $count = 4)
    ];
});
