@extends('layouts.master')

@section('title','Edit User')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-sm-8">
            <h2>Privacy Policy</h2>
            <p class="mt-3">
                Study Assistant is a time schedule management software that was created to assist students in managing their self study time schedules.
                Part of doing this process requires student study habit related data to be gathered and analyzed. This includes data such as:
            </p>
                <ul class="text-secondary">
                    <li>Student Basic Details (Age, University, Major, Country)</li>
                    <li>Schedule Details (Modules, Ratings, Grades)</li>
                    <li>Session Details (Completed Sessions, Missed Sessions)</li>
                </ul>

            <p>
                This data will only be used for the purpose of providing students with informative reports that assist them in their decision making
                process. Your data would not be shared with any third parties and under no circumstance will students be able to access the data of
                other individual students. However, you basic information can be viewed by administrators for management purposes.
            </p>
            <p>
                The purpose of gathering this data is to aggregate them and create statistics based on them. Your data, along with the
                data of other students are collected and analyzed to create informative reports about the modules you face. Your data is also
                used for the purpose of grade prediction, where an algorithm will analyze your data to make predictions about the grades of users.

            </p>
        </div>
    </div>
</div>

@endsection