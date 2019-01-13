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

$factory->define(App\User::class, function (Faker $faker) {
    $majors = [
        'Arts',
        'History',
        'Philosophy',
        'Theology',
        'Anthropology',
        'Archaeology',
        'Economics',
        'Law',
        'Psychology',
        'Sociology',
        'Biology',
        'Chemistry',
        'Physics',
        'Mathematics',
        'Statistics',
        'Engineering and technology'
    ];

    
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'email_verified_at' => now(),
        'birth' => $faker->year($max = 'now'),
        'gender' => $faker->randomElement($array = array('M','F')),
        'country' => 'UK',
        'university' => 'University of '.$faker->city,
        'major' => $majors[rand(0, count($majors) - 1)],
        'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
        'remember_token' => str_random(10),
    ];
});
