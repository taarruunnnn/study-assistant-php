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
                        <div>
                            <button class="startButton btn btn-primary btn-lg"><i class="fas fa-play"></i></button>
                            <button class="pauseButton btn btn-primary btn-lg" ><i class="fas fa-pause"></i></button>
                            <button class="stopButton btn btn-primary btn-lg"><i class="fas fa-stop"></i></button>
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
    </div>

@endsection

@section('script')
    <script>
        $(function(){
            var duration = "00:00:10";
            $sessionTimer = $('#sessionTimer .values');
            $sessionTimer.html(duration);

            var timer = new Timer();

            timer.addEventListener('secondsUpdated', function (e) {
                $sessionTimer.html(timer.getTimeValues().toString());
            });
            $('#sessionTimer .startButton').click(function () {
                timer.start({countdown: true, startValues: {seconds: 10}});
                window.onbeforeunload = function() {
                    return true;
                };
            });
            $('#sessionTimer .pauseButton').click(function () {
                timer.pause();
            });
            $('#sessionTimer .stopButton').click(function () {
                timer.stop();
                $sessionTimer.html(duration);
                window.onbeforeunload = null;
            });
            timer.addEventListener('started', function (e) {
                $sessionTimer.html(timer.getTimeValues().toString());
            });
            timer.addEventListener('targetAchieved', function (e) {
                window.onbeforeunload = null;
                $sessionTimer.html("Completed");
                sessionComplete();
            });

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