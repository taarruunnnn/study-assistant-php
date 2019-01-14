@extends('layouts.master')

@section('title','Study Session')

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-sm-3"><h6>Select a study session</h6></div>
        </div>
        <div class="row">
            <div class="col-sm-3">
                <select name="modules" id="modules" class="form-control">
                    @foreach ($modules as $module)
                        @if ($module['status'] == "incomplete")
                            <option value="{{ $module['id'] }}">{{ $module['module'] }}</option>
                        @endif
                       
                    @endforeach
                </select>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <div class="card mt-4">
                    <div id="sessionTimer" class="card-body">
                        <h5 class="card-title">Start the timer</h5>
                        <div class="values">00:00:00</div>
                        <h4 style="display: none;" class="my-3" id="breakText">Take a break</h4>
                        <div>
                            <button class="startButton btn btn-primary btn-lg" id="sessionStart"><i class="fas fa-play"></i></button>
                            <button class="pauseButton btn btn-primary btn-lg" id="sessionPause" ><i class="fas fa-pause"></i></button>
                            <button class="stopButton btn btn-primary btn-lg" id="sessionStop"><i class="fas fa-stop"></i></button>
                        </div>
                    </div>          
                </div>     
            </div>
            <div class="col-sm-6">
                <div class="card mt-4">
                    <div class="card-body">
                        <h4 class="card-title mb-4">Today's Sessions</h4>
                        <h6 class="card-subtitle mb-2 text-muted">To Study</h6>
                        <ul>
                            @foreach ($modules as $module)
                                @if ($module['status'] == "incomplete")
                                    <li>2 Hours of {{ $module['module'] }}</li>
                                @endif
                            @endforeach
                        </ul>
                        <h6 class="card-subtitle mb-2 text-muted">Completed</h6>
                        <ul>
                            @foreach ($modules as $module)
                                @if ($module['status'] == "completed")
                                    <li>2 Hours of {{ $module['module'] }}</li>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <div id="breakTimer" class="card mt-4 bg-secondary text-white" style="display: none;">
                    <div class="card-body">
                        <h5 class="card-title">Break Timer</h5>
                        <div class="values">00:00:00</div>
                        <div>
                            <button class="startButton btn btn-primary btn-lg"><i class="fas fa-play"></i></button>
                        </div>
                    </div>          
                </div>  
            </div>
        </div>
    </div>

@endsection

@section('script')
    <script>
        $(function(){
            timerInit(); 

            function timerInit()
            {
                var duration = "00:00:10";

                var $breakTimerDom = $('#breakTimer .values');
                var $sessionTimerDom = $('#sessionTimer .values');
                var breakDuration = "00:00:05";
                
                $sessionTimerDom.html(duration);

                var timer = new Timer();

                timer.addEventListener('secondsUpdated', function (e) {
                    var time = timer.getTimeValues().seconds;
                    if(time == 5)
                    {
                        timer.pause();
                        $('#breakText').show();
                        $('#breakTimer').slideDown("slow");
                        $breakTimerDom.text(breakDuration);

                    }
                    $sessionTimerDom.html(timer.getTimeValues().toString());
                });

                $('#sessionTimer .startButton').click(function () {
                    if( $('#modules').val() ) 
                    {
                        timer.start({countdown: true, startValues: {seconds: 10}});
                        window.onbeforeunload = function() {
                            return true;
                        };
                        $('#breakTimer').hide("slow");
                        $('#breakText').hide("slow");
                    }
                    
                });

                $('#sessionTimer .pauseButton').click(function () {
                    timer.pause();
                });

                $('#sessionTimer .stopButton').click(function () {
                    timer.stop();
                    $sessionTimerDom.html(duration);
                    window.onbeforeunload = null;
                });

                timer.addEventListener('started', function (e) {
                    $sessionTimerDom.html(timer.getTimeValues().toString());
                });

                timer.addEventListener('targetAchieved', function (e) {
                    window.onbeforeunload = null;
                    $sessionTimerDom.html("Completed");
                    sessionComplete();
                });
    
                $breakTimerDom.html(breakDuration);

                var breakTimer = new Timer();

                breakTimer.addEventListener('secondsUpdated', function (e) {
                    $breakTimerDom.html(breakTimer.getTimeValues().toString());
                });
                $('#breakTimer .startButton').click(function () {
                    if( $('#modules').val() ) 
                    {
                        breakTimer.start({countdown: true, startValues: {seconds: 05}});
                        window.onbeforeunload = function() {
                            return true;
                        };
                    }
                    
                });
                breakTimer.addEventListener('started', function (e) {
                    $breakTimerDom.html(timer.getTimeValues().toString());
                });
                breakTimer.addEventListener('targetAchieved', function (e) {
                    $breakTimerDom.html("Complete");
                    $('#breakText').text("Break completed. Resume studying.");
                    window.onbeforeunload = null;
                });
            }

            
            function sessionComplete()
            {
                var sessionId = $('#modules').val();

                $.ajax({
                    type: 'GET',
                    url: '{{ route('session.complete') }}',
                    data: {sessionId: sessionId},
                    success: function(message){
                        console.log("success" + message);
                        location.reload();
                    },
                    error: function(message){
                        console.log(message);
                    }
                });
            }
        });
    </script>
@endsection