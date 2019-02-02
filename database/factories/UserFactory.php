<?php

use Faker\Generator as Faker;

/**
 * User Factory
 * 
 * This is used as part of a database seeding operation
 * where random user accounts will be created.
 * This is only used for testing purposes where the database
 * needs to be populated
 */

$factory->define(
    App\User::class, function (Faker $faker) {
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
            'created_at' => $faker->dateTimeBetween($startDate = '-2 years', $endDate = 'now', $timezone = null),
            'remember_token' => str_random(10),
        ];
    }
);
