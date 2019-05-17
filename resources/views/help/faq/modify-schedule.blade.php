@extends('layouts.master')

@section('title','Help')

@section('content')

    <div class="container">
        <div class="row">
            <div class="col mb-3">
                <h4>Modify Schedule</h4>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-9">
                <p>
                    Once a schedule has been created, you can easily modify it if it doesn't suit your needs. 
                    There are two ways to modify a schedule. You could <strong>Move Events</strong> if you need to change study sessions,
                    or you could <strong>Modify Entire Schedule</strong>. Doing so would remove all your previous progress and create a new
                    schedule for you.
                </p>
                <div class="row">
                    <div class="col">
                        <h5 class="my-3">Move Sessions</h5>
                        <img src="{{ asset('storage/images/screenshots/faq/modify-schedule/move-sessions.gif') }}" alt="Move Sessions" class="img-fluid" width="800">
                        <p class="my-3">
                            Sessions can be moved to other dates by clicking on a session inside the calendar, clicking on 'Change Session Date'
                            and finally inputing the new date.
                        </p>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <h5 class="my-3">Modify Schedule</h5>
                        <img src="{{ asset('storage/images/screenshots/faq/modify-schedule/modify-schedule.gif') }}" alt="Move Sessions" class="img-fluid" width="800">
                        <p class="my-3">
                            If you want to modify the entire schedule instead, you click on 'Modify Schedule' and change the schedule details and even 
                            add or remove modules as you wish.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection