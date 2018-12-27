@extends('layouts.master')

@section('title','Reports')

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-sm-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-4">Schedule Summary</h4>
                        <div class="report-body">
                            @if ($live == false)
                                <p class="text-dark">Time of Report : 
                                    <span class="text-primary">{{$date}}</span>
                                </p>
                            @endif

                            <p class="text-dark">Number of Modules in this Schedule : 
                                <span class="text-primary">
                                    @if (! empty($modules))
                                        {{ $modules->count() }}
                                    @elseif ($live == false)
                                        {{ $modulesCount }}
                                    @endif
                                </span>
                            </p>

                            <p class="text-dark">Number of Sessions Completed : 
                                <span class="text-primary">    
                                    @if (! empty($sessions))
                                        {{ $sessions->where('status', 'completed')->count() }}
                                    @elseif ($live == false)
                                        {{ $sessionsComplete }}
                                    @endif
                                </span>
                            </p>
                            
                            <p class="text-dark">Number of Sessions Missed : 
                                <span class="text-primary">
                                    @if (! empty($modules))
                                        {{ $sessions->where('status', 'failed')->count() }}
                                    @elseif ($live == false)
                                        {{ $sessionsMissed }}
                                    @endif
                                </span>
                            </p>

                            <p class="text-dark">Number of Sessions to Complete : 
                                <span class="text-primary">
                                    @if (! empty($modules))
                                        {{ $sessions->where('status', 'incomplete')->count() }}
                                    @elseif ($live == false)
                                        {{ $sessionsIncomplete }}
                                    @endif
                                </span>
                            </p>

                            <p class="text-dark">Progress of Schedule : 
                                <span class="text-primary">
                                    @if (! empty($progress))
                                        {{ $progress }}&#37;
                                    @else
                                        0
                                    @endif
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
                @if ((! empty($schedule)) || $live == false)
                <div class="card mt-3" style="display:none" id="canvasCompare">
                    <div class="card-body">
                        <canvas id="chartCompare" width="400" height="300"></canvas>
                    </div>
                </div>
                @endif
            </div>
            <div class="col-sm-6">
                @if ((! empty($schedule)) || $live == false)
                <div class="card" style="display:none" id="canvasProgress">
                    <div class="card-body">
                        <canvas id="chartProgress" width="400" height="300"></canvas>
                    </div>
                </div>
                @endif
            </div>
        </div>
        <div class="row">
            @if (! empty($schedule))
                <form action="{{ route('report.save') }}" method="POST" class="m-3" id="reportForm">
                    @csrf
                    <button type="submit" class="btn btn-secondary">Save Report</button>
                </form>
            @endif
        </div>
    </div>

@endsection

@section('script')
<script>
    $(document).ready(function(){

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        @if (! empty($schedule) && $live == true)
            var schedule_id = {{ $schedule->id }}
            analyze(schedule_id)
        @elseif ($live == false)
            var data = {!! json_encode($data) !!}
            data = data.replace(/\\/g, "");
            data = JSON.parse(data)
            displayAnalysis(data)
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
                    console.log(message);
                }
            });
        }

        function displayAnalysis(data)
        {
            if (data['sessions'] != null)
            {
                $("#canvasProgress").show();

                var ctx = document.getElementById("chartProgress");

                @if ($live == true)
                    var completed_sessions = JSON.parse(data.sessions).Completed;
                    var total_sessions = JSON.parse(data.sessions).Total;
                @else
                    var completed_sessions = data.sessions.Completed;
                    var total_sessions = data.sessions.Total;
                @endif

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

                @if ($live == true)
                    var input = document.createElement("input");
                    input.setAttribute("type", "hidden");
                    input.setAttribute("name", "sessions");
                    input.setAttribute("value", data['sessions']);

                    document.getElementById("reportForm").appendChild(input);
                @endif
            }

            if (data['comparedtime'] != "N/A")
            {
                $("#canvasCompare").show();
                var ctx2 = document.getElementById("chartCompare");

                @if ($live == true)
                    var student_data = JSON.parse(data.comparedtime).Student;
                    var others_data = JSON.parse(data.comparedtime).Others;
                @else
                    var student_data = data.comparedtime.Student;
                    var others_data = data.comparedtime.Others;
                @endif

                var modules = new Array();
                var student_count = new Array();
                var others_count = new Array();

                for (var comparedtime in student_data) {
                    if (student_data.hasOwnProperty(comparedtime)) {
                        modules.push(comparedtime);
                        student_count.push(student_data[comparedtime])
                    }
                }

                for (var comparedtime in others_data) {
                    if (others_data.hasOwnProperty(comparedtime)) {
                        others_count.push(others_data[comparedtime])
                    }
                }

                new Chart(ctx2, {
                    type: 'bar',
                    data: {
                    labels: modules,
                    datasets: [
                        {
                            label: "You",
                            backgroundColor: "#3e95cd",
                            data: student_count
                        }, {
                            label: "Average Student",
                            backgroundColor: "#8e5ea2",
                            data: others_count
                        }
                    ]
                    },
                    options: {
                        title: {
                            display: true,
                            text: 'Number of Hours Spent per Module'
                        },
                        scales: {
                            yAxes: [{
                                scaleLabel: {
                                    display: true,
                                    labelString: 'Hours',
                                    fontColor: '#9c9c9c'
                                }
                            }],
                            xAxes: [{
                                scaleLabel: {
                                    display: true,
                                    labelString: 'Modules',
                                    fontColor: '#9c9c9c'
                                }
                            }]
                        }
                    }
                });

                @if ($live == true)
                var input = document.createElement("input");
                input.setAttribute("type", "hidden");
                input.setAttribute("name", "comparedtime");
                input.setAttribute("value", data['comparedtime']);

                document.getElementById("reportForm").appendChild(input);
                @endif
            }
        }


    });

</script>
@endsection