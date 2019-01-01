@extends('layouts.master')

@section('title','Dashboard')

@section('content')

<div class="container-fluid" id="dash">
    <div class="row ml-2">
        <div class="col-md-3">
            <div class="card h-100">
                <div class="card-body d-flex align-items-center flex-column">
                    <h5 class="card-title dash-title text-center">Hello {{ Auth::user()->name }}!</h5>
                    <hr class="w-100">
                    <div class="my-auto">    
                        <div>
                            <p>{{$quote['quote']}}</p>
                            <p><em>- {{$quote['author']}}</em></p>
                        </div>
                    </div>
                    @if (! empty($schedule))
                        <a href="{{ route('session.show') }}" class="btn btn-primary">Start Studying</a>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title dash-title text-center">Schedule Completion</h5>
                    <hr/>
                    @if (! empty($schedule))
                        <p>You Have Completed<br/>
                        <span id="dash-percentage">{{ $progress }}%</span> <br/>
                        Of Your Schedule</p>
                        <hr>
                        <p>Schedule Started on <br/>{{ $schedule->start }}</p>
                        <p>Schedule Ends on <br/>{{ $schedule->end }}</p>
                    @else
                        <p>No Schedule data to display</p>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title dash-title text-center">Modules Summary</h5>
                    <hr/>
                    @if (! empty($schedule))
                        <canvas id="pie-chart" width="250" height="350"></canvas>
                    @else
                        <p>No Schedule data to display</p>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title dash-title text-center">Today's Summary</h5>
                    <hr/>
                    <div class="moment my-2">
                        <div id ="date"></div>
                        <div id="time"></div>        
                    </div>
                    <hr/>
                    @if (! empty($module_list))
                        <p>Your Sessions For Today Are :</p>
                        <ul>
                            @foreach ($module_list as $module)
                                <li>{{ $module }} - 2Hrs</li> 
                            @endforeach
                        </ul>
                    @else
                        <p>No Schedule data to display</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-3 ml-2">
        @if (! empty($schedule))
            <div class="col-md-9">
                <div class="card">
                    <div class="card-body">
                        <h2 class="card-title dash-title text-center">Study Sessions</h2>
                        <hr>
                        <div id="sessions-div">
                            <canvas id="chartProgress" width="500" height="200"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title dash-title text-center">Schedule Summary</h5>
                        <hr/>
                        <p>You have finished {{ $finished }} sessions</p>
                        <p>You have {{ $left }} sessions left</p>
                        <p>You have missed {{ $missed }} sessions</p>
                    </div>
                </div>
            </div>
        @else
            <div class="col text-center mt-5">
                <a href="{{ route('schedules.create') }}" class="btn btn-primary btn-lg">CREATE NEW SCHEDULE</a>
            </div>
        @endif
    </div>
    
</div>

@stop

@section('script')
    <script>
        $(document).ready(function(){

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            @if (! empty($schedule))
                var schedule_id = {{ $schedule->id }}
                analyze(schedule_id)
            @endif


            function analyze(schedule_id)
            {
                $.ajax({
                    type: 'POST',
                    url: '{{ route('report.analyze') }}',
                    data: {schedule: schedule_id},
                    success: function(data){
                        displayAnalysis(data)
                        console.log(data)
                    },
                    error: function(message){
                        console.log('Failed '.message);
                        $("#sessions-div").html("<h4 class='text-center'>No Data To Display</h4>");
                    }
                });
            }

            @if (! empty($modules))
                var modules = new Array();
                var ratings = new Array();
                @foreach ($modules as $module)
                    modules.push('{{$module->name}}')
                    ratings.push('{{$module->rating}}')
                @endforeach

                new Chart(document.getElementById("pie-chart"), {
                    type: 'pie',
                    data: {
                    labels: modules,
                    datasets: [{
                        label: "Rating",
                        backgroundColor: ["#00bcd4", "#2b8cba", "#3f51b5", "#9c27b0", "#e91e63", "#e65100", "#8bc34a", "#4caf50", "#797979", "#2196f3"],
                        data: ratings
                    }]
                    },
                    options: {
                    title: {
                        display: true,
                        text: 'Modules & their Ratings in Current Schedule'
                    },
                    legend: {
                        display: true,
                        position: 'bottom',
                        labels: {
                            fontSize: 9
                            }
                    }
                    }
                });
            @endif


        function displayAnalysis(data)
        {
            if (data['sessions'] != null)
            {
                $("#canvasProgress").show();

                var ctx = document.getElementById("chartProgress");

 
                var completed_sessions = JSON.parse(data.sessions).Completed;
                var total_sessions = JSON.parse(data.sessions).Total;


                var months = new Array();
                var completed_count = new Array();
                var total_count = new Array();

                for (var session in completed_sessions) {
                    if (completed_sessions.hasOwnProperty(session)) {
                        months.push(session);
                        completed_count.push(completed_sessions[session])
                    }
                }

                for (var session in total_sessions) {
                    if (total_sessions.hasOwnProperty(session)) {
                        total_count.push(total_sessions[session])
                    }
                }

                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: months,
                        datasets: [{
                            data: completed_count,
                            label: "Completed Sessions",
                            borderColor: "#38c172",
                            fill: false
                        },
                        {
                            data: total_count,
                            label: "Scheduled Sessions",
                            borderColor: "#3e95cd",
                            fill: false
                        }]
                    },
                    options: {
                        title: {
                            display: true,
                            text: 'Schedule Progress',
                        },
                        scales: {
                            yAxes: [{
                                scaleLabel: {
                                    display: true,
                                    labelString: 'No. of Sessions',
                                    fontColor: '#9c9c9c'
                                }
                            }],
                            xAxes: [{
                                scaleLabel: {
                                    display: true,
                                    labelString: 'Months',
                                    fontColor: '#9c9c9c'
                                }
                            }]
                        }
                    }
                });
            }
        }

            
        function displayTime() {
            var time = moment().format('HH:mm:ss');
            var date = moment().format('MMMM Do YYYY')
            $('#time').html(time);
            $('#date').html(date);
            setTimeout(displayTime, 1000);
        }

        displayTime();

        })
    </script>
@endsection
