<?php

use Faker\Generator as Faker;
use App\Http\Requests\StoreSchedule;

/**
 * Completed Module Factory
 * 
 * This is used as part of a database seeding operation
 * where completed modules will be assigned to users.
 * This is only used for testing purposes where the database
 * needs to be populated
 */

$factory->define(
    App\CompletedModule::class, function (Faker $faker) {
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

        $ratArray = array(1, 10);

        $rating = $ratArray[array_rand($ratArray)];


        if ($rating == 1) {
            $completed = $faker->numberBetween($min = 50, $max = 60);
            $failed = $faker->numberBetween($min = 1, $max = 5);
            $grade = $faker->randomElement($array = array('A+', 'A', 'A-'));
        } elseif ($rating == 10) {
            $completed = $faker->numberBetween($min = 60, $max = 70);
            $failed = $faker->numberBetween($min = 10, $max = 35);
            $grade = $faker->randomElement($array = array('C', 'C-', 'F', ));
        }
                
        
        return [
            'name' => $faker->randomElement($array = $modules),
            'rating' => $rating,
            'grade' => $grade,
            'completed_sessions' => $completed,
            'failed_sessions' => $failed
        ];
    }
);
