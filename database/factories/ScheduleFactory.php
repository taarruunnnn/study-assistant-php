<?php

use Faker\Generator as Faker;
use App\Http\Requests\StoreSchedule;

/**
 * Schedule Factory
 * 
 * This is used as part of a database seeding operation
 * where schedules will be assigned to users.
 * This is only used for testing purposes where the database
 * needs to be populated
 */

$factory->define(
    App\Schedule::class, function (Faker $faker) {
        $modules = [
            'Fundamentals of Programming',
            'Web Technologies',
            'Visual Application Programming',
            'Computer Organization',
            'Introduction to Networking',
            'Management',
            'Mathematics for IT',
            'Database Management Systems',
            'Software Engineering',
            'Computer Architecture',
            'Network Programming',
            'Multimedia Design',
            'Accountancy',
            'Business Studies',
            'Data Structures & Algorithms',
            'Logic Programming',
            'Computational Mathematics',
            'Human Resource Management',
            'Object Oriented Analysis & Design',
            'Essentials of Machine Learning',
            'IT Project Management',
            'Professional Practice',
            'Data Mining & Warehousing',
            'IT Quality Assurance',
            'Intelligent Systems'
        ];

        $start = $faker->dateTimeBetween(
            $startDate = '2018-10-01',
            $endDate = '2019-05-01', 
            $timezone = null
        );

        $start = $start->format("Y-m-d");
        $end = $faker->dateTimeBetween(
            $startDate = '2019-06-30', 
            $endDate = '2019-10-01', 
            $timezone = null
        );

        $end = $end->format("Y-m-d");
        
        return [
            'test' => '1',
            'start' => $start,
            'end' => $end,
            'weekdays' => $faker->randomElement($array = array('2','4','6')),
            'weekends' => $faker->randomElement($array = array('2','4','6')),
            'module' => $faker->randomElements($array = $modules, $count = 4),
            'rating' => $faker->randomElements(
                $array = array(
                    '1', '2', '3', '4', '5', '6', '7', '8', '9', '10'
                ), $count = 4
            )
        ];
    }
);
