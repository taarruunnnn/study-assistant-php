@extends('layouts.master')

@section('title','Study Session')

@section('content')

    <div class="container">
        <div class="row" id="ajaxWarning" style="display:none;">
            <div class="col">
                <div class="alert alert-danger animated fadeIn" role="alert">
                    Session Failed : Could not connect the server.
                </div>
            </div>
        </div>
        <div class="row mt-3 animated fadeIn">
            <div class="col-sm-6 text-center mx-auto">
                <h5 id="sessionHeader">Select a module to start studying</h5>
                <select name="modules" id="modules" class="form-control my-3">
                    @foreach ($modules as $module)
                        @if ($module['status'] == "incomplete")
                            <option value="{{ $module['id'] }}">{{ $module['module'] }}</option>
                        @endif
                    @endforeach
                </select>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6 mx-auto">
                <div class="card text-white animated fadeIn" id="sessionTimer">
                    <div class="card-body text-center">
                        <div class="values mx-auto">00:00:00</div>
                        <div class="text-center">
                            <div class="mx-auto text-info my-3" id="breakTimer">Break Timer : <span id="breakValues">00:00:00</span></div>
                        </div>
                        <div class="text-center">
                            <h3 class="text-success" style="display:none;" id="sessionCompleteText">Session Complete</h3>
                        </div>
                        
                        <div class="text-center">
                            <button class="timerBtn btn btn-danger btn-lg" id="sessionStart"><i class="fas fa-play"></i></button>
                            <button class="timerBtn btn btn-danger btn-lg" id="sessionStop"><i class="fas fa-stop"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6 mx-auto mt-1 text-center">
                <p>Break recommended every <span id="break-text">00:00:00</span></p>
            </div>
        </div>
        <div class="row my-4">
            <div class="col-sm-9 mx-auto">
                <table class="table table-bordered" id="module-table" style="display:none;">
                    <thead>
                        <tr>
                            <th scope="col" style="width:50%" class="text-center">To Complete</th>
                            <th scope="col" style="width:50%" class="text-center">Completed</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                    </tbody>
                </table>
            </div>
        </div>
        <audio src="{{ asset('storage/audio/pause.mp3') }}" id="pauseAudio" preload="auto" style="display:none;"></audio>
        <audio src="{{ asset('storage/audio/success.mp3') }}" id="successAudio" preload="auto" style="display:none;"></audio>
    </div>

@endsection

@section('script')
    <script>
        $(function(){
            //DOM Assignments
            var $modules = $('#modules');
            var $sessionStop = $('#sessionStop');
            var $sessionStart = $('#sessionStart');
            var $sessionCompleteText = $('#sessionCompleteText');
            var $breakTimerDom = $('#breakValues');
            var $sessionTimerDom = $('#sessionTimer .values');
            var $breakTimer = $('#breakTimer');

            var $successAudio = $('#successAudio');
            var $pauseAudio = $('#pauseAudio');

            var completed = [];
            var incomplete = [];
        
            (function ()
            {
                @if ($modules)
                    @foreach ($modules as $module)
                        @if ($module['status'] == "incomplete")
                            incomplete.push('{!! $module["module"] !!}');
                        @elseif ($module['status'] == "completed")
                            completed.push('{!! $module["module"] !!}');
                        @endif
                    @endforeach
                @endif

                populateTable();
                populateDropdown();

                $modules.on('change', function(){
                    $sessionStop.trigger('click');
                    $sessionCompleteText.hide('slow');
                });


                var timerHours = 00; 
                var timerMinutes = 00;
                var timerSeconds = 10;
                var timerTotalInSeconds = (timerHours*60*60) + (timerMinutes*60) + timerSeconds;

                var breakStartSeconds = 5; // 40 minutes = 2400 seconds

                var breakDurationMinutes = 00;
                var breakDurationSeconds = 04;

                var breakTextHours = (breakStartSeconds / (60 * 60)).toString();
                var breakTextMinutes = (breakStartSeconds % 60*60).toString();
                var breakTextSeconds = (breakStartSeconds % 60).toString();
                
                $('#break-text').text(moment.utc(breakStartSeconds*1000).format('HH:mm:ss'));
                var durationText = ("0" + timerHours).slice(-2) + ":" + ("0" + timerMinutes).slice(-2) + ":" + ("0" + timerSeconds).slice(-2);
                var breakDurationText = "00:" + ("0" + breakDurationMinutes).slice(-2) + ":" + ("0" + breakDurationSeconds).slice(-2);

                $breakTimerDom.text(breakDurationText);
                $sessionTimerDom.text(durationText);

                //Countdown Timer

                var timer = new Timer();
                var breakTimer = new Timer();
                var play = false;
                var secs = 0;
                var totalSecs = 0;

                $sessionStart.click(function () {
                    if( $modules.val() )
                    {
                        $breakTimer.hide("slow");
                        breakTimer.stop();
                        $sessionCompleteText.hide('slow');

                        if (!(play))
                        {
                            timer.start({countdown: true, startValues: {seconds: timerSeconds, minutes: timerMinutes, hours: timerHours}});
                            $sessionStop.prop('disabled', false);
                            $sessionStart.html('<i class="fas fa-pause"></i>')
                            play = true;
                            window.onbeforeunload = function(){
                                return true;
                            };
                        } else {
                            $sessionStart.html('<i class="fas fa-play"></i>');
                            play = false;
                            timer.pause();
                        }
                    }
                });

                $sessionStop.click(function () {
                    secs = 0;
                    totalSecs = 0;
                    $sessionStart.html('<i class="fas fa-play"></i>')
                    play = false;
                    timer.stop();
                    breakTimer.stop();
                    $sessionTimerDom.html(durationText);
                    window.onbeforeunload = null;
                });
                
                
                timer.addEventListener('secondsUpdated', function (e) {
                    var time = timer.getTimeValues();
                    secs++;
                    totalSecs++;

                    if(secs == breakStartSeconds && totalSecs != timerTotalInSeconds)
                    {
                        secs = 0;
                        timer.pause();
                        $sessionStart.html('<i class="fas fa-play"></i>')
                        play = false;
                        $sessionStop.prop('disabled', 'disabled');
                        $pauseAudio.trigger('play');

                        breakTimer.start({countdown: true, startValues: {seconds: breakDurationSeconds, minutes: breakDurationMinutes}});
                        $breakTimer.show(500).css("display", "inline-block");
                        $breakTimerDom.text(breakDurationText);
                    }
                    $sessionTimerDom.html(timer.getTimeValues().toString());
                });

                timer.addEventListener('started', function (e) {
                    $sessionTimerDom.html(timer.getTimeValues().toString());
                });

                timer.addEventListener('targetAchieved', function (e) {
                    secs = 0;
                    totalSecs = 0;
                    window.onbeforeunload = null;
                    $successAudio.trigger('play');
                    $sessionCompleteText.show('slow');
                    sessionComplete();
                });

                breakTimer.addEventListener('secondsUpdated', function (e) {
                    $breakTimerDom.html(breakTimer.getTimeValues().toString());
                });

                breakTimer.addEventListener('started', function (e) {
                    $breakTimerDom.html(timer.getTimeValues().toString());
                });
                breakTimer.addEventListener('targetAchieved', function (e) {
                    $breakTimerDom.html("Complete");
                    $sessionStop.prop('disabled', false);
                    $successAudio.trigger('play');
                    window.onbeforeunload = null;
                });
            })();

            
            function sessionComplete()
            {
                var sessionId = $modules.val();
                var sessionName = $('#modules option:selected').text();

                $.ajax({
                    type: 'GET',
                    url: '{{ route('session.complete') }}',
                    data: {sessionId: sessionId},
                    success: function(message){
                        sessionSuccess(sessionName);
                        populateDropdown();
                        $sessionStop.trigger('click');
                        toastr.success("Session Completed");
                    },
                    error: function(message){
                        $('#ajaxWarning').show();
                    }
                });
            }

            function sessionSuccess(sessionName)
            {
                var index = incomplete.indexOf(sessionName);
                if (index > -1)
                {
                    incomplete.splice(index, 1);
                    completed.push(sessionName);
                }

                $modules.find('option:selected').remove();

                populateTable();
            }

            function populateTable()
            {
                $('#module-table > tbody').html("");
                
                var length = incomplete.length;
                if (completed.length > incomplete.length)
                {
                    length = completed.length;
                }

                for (x = 0; x <= length; x++)
                {
                    var comp = "";
                    var incomp = "";
                    if (!(typeof completed[x] === 'undefined'))
                    {
                        comp = completed[x] + '<span class="text-secondary"> - (2 Hours)</span>';
                    }
                    if (!(typeof incomplete[x] === 'undefined'))
                    {
                        incomp = incomplete[x] + '<span class="text-secondary"> - (2 Hours)</span>';
                    }
                    $('#module-table > tbody:last-child').append('<tr><td>'+incomp+'</td><td>'+comp+'</td></td>');
                }

                $('#module-table').show('slow');
            }

            function populateDropdown()
            {
                if ($('#modules option').length == 0)
                {
                    $('#sessionHeader').text('No Sessions for today');
                    $modules.prop('disabled', true);
                    $sessionStart.prop('disabled', true);
                    $sessionStop.prop('disabled', true);
                    return;
                }
            }
        });
    </script>
@endsection