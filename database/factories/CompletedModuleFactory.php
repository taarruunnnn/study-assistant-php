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
            array('Fundamentals of Programming', 'N'),
            array('Web Technologies', 'E'),
            array('Visual Application Programming', 'N'),
            array('Computer Organization', 'H'),
            array('Introduction to Networking', 'N'),
            array('Management', 'E'),
            array('Mathematics for IT', 'H'),
            array('Database Management Systems', 'H'),
            array('Software Engineering', 'N'),
            array('Computer Architecture', 'H'),
            array('Network Programming', 'N'),
            array('Multimedia Design', 'N'),
            array('Accountancy', 'H'),
            array('Business Studies', 'E'),
            array('Data Structures & Algorithms', 'H'),
            array('Logic Programming', 'H'),
            array('Computational Mathematics', 'H'),
            array('Human Resource Management', 'E'),
            array('Object Oriented Analysis & Design', 'N'),
            array('Essentials of Machine Learning', 'H'),
            array('IT Project Management', 'N'),
            array('Professional Practice', 'E'),
            array('Data Mining & Warehousing', 'H'),
            array('IT Quality Assurance', 'E'),
            array('Intelligent Systems', 'H')
        ];

        $moduleKey = array_rand($modules);
        $module = $modules[$moduleKey][0];
        $difficulty = $modules[$moduleKey][1]; 

        if ($difficulty == 'E') {
            $rating = $faker->numberBetween($min = 1, $max = 3);
        } elseif ($difficulty == 'N') {
            $rating = $faker->numberBetween($min = 4, $max = 7);
        } else {
            $rating = $faker->numberBetween($min = 8, $max = 10);
        }


        switch (true) {
        case ($rating < 3):

            $randomizer = array(1, 2, 3, 4, 5);
            $rand = $randomizer[array_rand($randomizer)];

            if ($rand == 1) {
                $completed = $faker->numberBetween($min = 90, $max = 100);
                $failed = 100 - $completed;
                $grade = $faker->randomElement($array = array('A+'));
            } else if ($rand == 2) {
                $completed = $faker->numberBetween($min = 75, $max = 90);
                $failed = 100 - $completed;
                $grade = $faker->randomElement($array = array('A', 'B+'));
            } else if ($rand == 3) {
                $completed = $faker->numberBetween($min = 55, $max = 75);
                $failed = 100 - $completed;
                $grade = $faker->randomElement($array = array('B-'));
            } else if ($rand == 4) {
                $completed = $faker->numberBetween($min = 40, $max = 55);
                $failed = 100 - $completed;
                $grade = $faker->randomElement($array = array('C'));
            } else {
                $completed = $faker->numberBetween($min = 0, $max = 30);
                $failed = 100 - $completed;
                $grade = $faker->randomElement($array = array('F'));
            } 
            break;

        case ($rating >= 3 && $rating < 5):

            $randomizer = array(1, 2, 3, 4);
            $rand = $randomizer[array_rand($randomizer)];

            if ($rand == 1) {
                $completed = $faker->numberBetween($min = 90, $max = 100);
                $failed = 100 - $completed;
                $grade = $faker->randomElement($array = array('A'));
            } else if ($rand == 2) {
                $completed = $faker->numberBetween($min = 70, $max = 90);
                $failed = 100 - $completed;
                $grade = $faker->randomElement($array = array('B'));
            } else if ($rand == 3) {
                $completed = $faker->numberBetween($min = 45, $max = 55);
                $failed = 100 - $completed;
                $grade = $faker->randomElement($array = array('C'));
            } else {
                $completed = $faker->numberBetween($min = 0, $max = 10);
                $failed = 100 - $completed;
                $grade = $faker->randomElement($array = array('F'));
            }
            break;

        case ($rating >= 5 && $rating < 7):

            $randomizer = array(1, 2, 3, 4);
            $rand = $randomizer[array_rand($randomizer)];

            if ($rand == 1) {
                $completed = $faker->numberBetween($min = 95, $max = 100);
                $failed = 100 - $completed;
                $grade = $faker->randomElement($array = array('A', 'B+'));
            } else if ($rand == 2) {
                $completed = $faker->numberBetween($min = 55, $max = 85);
                $failed = 100 - $completed;
                $grade = $faker->randomElement($array = array('B'));
            } else if ($rand == 3) {
                $completed = $faker->numberBetween($min = 45, $max = 55);
                $failed = 100 - $completed;
                $grade = $faker->randomElement($array = array('C', 'C-'));
            } else {
                $completed = $faker->numberBetween($min = 0, $max = 10);
                $failed = 100 - $completed;
                $grade = 'F';
            }
            break;

        case ($rating >= 7 && $rating <= 9):
        
            $randomizer = array(1, 2, 3, 4, 5);
            $rand = $randomizer[array_rand($randomizer)];

            if ($rand == 1) {
                $completed = $faker->numberBetween($min = 96, $max = 100);
                $failed = 100 - $completed;
                $grade = $faker->randomElement($array = array('A-'));
            } else if ($rand == 2) {
                $completed = $faker->numberBetween($min = 80, $max = 90);
                $failed = 100 - $completed;
                $grade = $faker->randomElement($array = array('B'));
            } else if ($rand == 3) {
                $completed = $faker->numberBetween($min = 65, $max = 79);
                $failed = 100 - $completed;
                $grade = $faker->randomElement($array = array('C'));
            } else if ($rand == 4) {
                $completed = $faker->numberBetween($min = 45, $max = 64);
                $failed = 100 - $completed;
                $grade = $faker->randomElement($array = array('D'));
            } else {
                $completed = $faker->numberBetween($min = 0, $max = 40);
                $failed = 100 - $completed;
                $grade = 'F';
            }
            break;

        case ($rating == 10):

            $randomizer = array(1, 2, 3, 4, 5);
            $rand = $randomizer[array_rand($randomizer)];

            if ($rand == 1) {
                $completed = $faker->numberBetween($min = 95, $max = 100);
                $failed = 100 - $completed;
                $grade = $faker->randomElement($array = array('A', 'B'));
            } else if ($rand == 2) {
                $completed = $faker->numberBetween($min = 75, $max = 90);
                $failed = 100 - $completed;
                $grade = $faker->randomElement($array = array('C+'));
            } else if ($rand == 3) {
                $completed = $faker->numberBetween($min = 55, $max = 70);
                $failed = 100 - $completed;
                $grade = $faker->randomElement($array = array('C-'));
            } else if ($rand == 4) {
                $completed = $faker->numberBetween($min = 40, $max = 45);
                $failed = 100 - $completed;
                $grade = $faker->randomElement($array = array('D'));
            } else {
                $completed = $faker->numberBetween($min = 0, $max = 40);
                $failed = 100 - $completed;
                $grade = 'F';
            }
            break;

        default:
            $rating = 10;
            $completed = $faker->numberBetween($min = 90, $max = 100);
            $failed = 100 - $completed;
            $grade = $faker->randomElement($array = array('B', 'B-'));
        }
                
        
        return [
            'name' => $module,
            'rating' => $rating,
            'grade' => $grade,
            'completed_sessions' => $completed,
            'failed_sessions' => $failed
        ];
    }
);
