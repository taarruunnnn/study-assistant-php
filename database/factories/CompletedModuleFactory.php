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

        $ratArray = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10);

        $rating = $ratArray[array_rand($ratArray)];

        $randomizer = array(1, 2, 3);


        switch (true) {
        case ($rating < 3):
            $rand = $randomizer[array_rand($randomizer)];

            if ($rand == 1) {
                $completed = $faker->numberBetween($min = 90, $max = 100);
                $failed = 100 - $completed;
                $grade = $faker->randomElement($array = array('A+'));
            } else {
                $completed = $faker->numberBetween($min = 0, $max = 10);
                $failed = 100 - $completed;
                $grade = 'F';
            } 
            break;

        case ($rating >= 3 && $rating < 5):
            $rand = $randomizer[array_rand($randomizer)];

            if ($rand == 1) {
                $completed = $faker->numberBetween($min = 90, $max = 100);
                $failed = 100 - $completed;
                $grade = $faker->randomElement($array = array('A+', 'A'));
            } else if ($rand == 2) {
                $completed = $faker->numberBetween($min = 45, $max = 55);
                $failed = 100 - $completed;
                $grade = 'C+';
            } else {
                $completed = $faker->numberBetween($min = 0, $max = 10);
                $failed = 100 - $completed;
                $grade = 'F';
            }
            break;

        case ($rating >= 5 && $rating < 7):
            $rand = $randomizer[array_rand($randomizer)];

            if ($rand == 1) {
                $completed = $faker->numberBetween($min = 90, $max = 100);
                $failed = 100 - $completed;
                $grade = $faker->randomElement($array = array('A+', 'A'));
            } else if ($rand == 2) {
                $completed = $faker->numberBetween($min = 45, $max = 55);
                $failed = 100 - $completed;
                $grade = 'C-';
            } else {
                $completed = $faker->numberBetween($min = 0, $max = 10);
                $failed = 100 - $completed;
                $grade = 'F';
            }
            break;

        case ($rating >= 7 && $rating <= 9):
            $randomizer = array(1, 2, 3, 4);
            $rand = $randomizer[array_rand($randomizer)];

            if ($rand == 1) {
                $completed = $faker->numberBetween($min = 90, $max = 100);
                $failed = 100 - $completed;
                $grade = $faker->randomElement($array = array('B+', 'B'));
            } else if ($rand == 2) {
                $completed = $faker->numberBetween($min = 45, $max = 55);
                $failed = 100 - $completed;
                $grade = 'C-';
            } else if ($rand == 3) {
                $completed = $faker->numberBetween($min = 0, $max = 10);
                $failed = 100 - $completed;
                $grade = 'F';
            } else {
                $completed = $faker->numberBetween($min = 99, $max = 100);
                $failed = 100 - $completed;
                $grade = 'A+';
            }
            break;

        case ($rating == 10):
            $randomizer = array(1, 2, 3, 4);
            $rand = $randomizer[array_rand($randomizer)];

            if ($rand == 1) {
                $completed = $faker->numberBetween($min = 90, $max = 100);
                $failed = 100 - $completed;
                $grade = $faker->randomElement($array = array('B', 'B-'));
            } else if ($rand == 2) {
                $completed = $faker->numberBetween($min = 45, $max = 55);
                $failed = 100 - $completed;
                $grade = $faker->randomElement($array = array('C-', 'D'));
            } else if ($rand == 3) {
                $completed = $faker->numberBetween($min = 0, $max = 10);
                $failed = 100 - $completed;
                $grade = 'F';
            } else {
                $completed = $faker->numberBetween($min = 99, $max = 100);
                $failed = 100 - $completed;
                $grade = 'A';
            }
            break;

        default:
            $rating = 10;
            $completed = $faker->numberBetween($min = 90, $max = 100);
            $failed = 100 - $completed;
            $grade = $faker->randomElement($array = array('B', 'B-'));
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
