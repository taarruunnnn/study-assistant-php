@extends('layouts.master')

@section('title','Reports')

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-md-10">
                <p id="date" style="font-weight:400;"></p>
            </div>
            <div class="col-md-2">
                @if (! empty($schedule))
                    <form action="{{ route('report.save') }}" method="POST" id="reportForm">
                        @csrf
                        <button type="submit" class="btn btn-primary">Save Report</button>
                    </form>
                @endif
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="card mt-3 animated fadeIn">
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
                                        0&#37;
                                    @endif
                                </span>
                            </p>

                        </div>
                    </div>
                </div>
                @if ((! empty($schedule)) || $live == false)
                

                <div class="card mt-3" style="display:none" id="canvasSessionCount">
                    <div class="card-body">
                        <div class="px-4">
                            <canvas id="sessionCountChart" width="100" height="100"></canvas>
                        </div>
                    </div>
                </div>

                <div class="card mt-3" style="display:none" id="canvasTimes">
                    <div class="card-body">
                        <canvas id="chartTimes" width="400" height="300"></canvas>
                    </div>
                </div>

                @endif
            </div>
            <div class="col-md-6">
                @if ((! empty($schedule)) || $live == false)
                <div class="card mt-3" style="display:none" id="predCard">
                    <div class="card-body">
                        <h5 class="card-title">Predicted Grades</h5>
                        <p>Please note that prediction of grades is still in its beta stage and that its accuracy is low at this point. However, this level of accuracy improves with everyday as students input their study habit related data to the system.</p>
                        <table class="table table-bordered" id="predTable">
                            <thead>
                                <tr>
                                    <th scope="col">Module Name</th>
                                    <th scope="col">Prediction</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif

                @if ((! empty($schedule)) || $live == false)
                <div class="card mt-3" style="display:none" id="canvasProgress">
                    <div class="card-body">
                        <canvas id="chartProgress" width="400" height="300"></canvas>
                    </div>
                </div>
                @endif

                @if ((! empty($schedule)) || $live == false)


                <div class="card mt-3" style="display:none" id="canvasCompare">
                    <div class="card-body">
                        <canvas id="chartCompare" width="400" height="300"></canvas>
                    </div>
                </div>
                @endif
            </div>
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

        @if (!empty($schedule) && $live == true)
        
            (function ()
            {
                $('#date').text("Date - " + moment().format('MMMM Do YYYY, h:mm a'));

                var schedule_id = {{ $schedule->id }}

                $.ajax({
                    type: 'POST',
                    url: '{{ route('report.analyze') }}',
                    data: {schedule: schedule_id},
                    success: function(data){
                        displayAnalysis(data)
                    },
                    error: function(message){
                        console.log(message);
                    }
                });
                
            })();

        @elseif ($live == false)
        
            (function ()
            {
                var data = {!! json_encode($data) !!}
                data = data.replace(/\\/g, "");
                data = JSON.parse(data)
                displayAnalysis(data)
            })();

        @endif

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
                    createHiddenForm("sessions", data["sessions"]);
                @endif
            }

            if (!(data['comparedtime'] == "N/A" || data['comparedtime'] == null))
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
                    createHiddenForm("comparedtime", data['comparedtime']);
                @endif
            }

            if (!(data['studytimes'] == "N/A" || data['studytimes'] == null))
            {
                $("#canvasTimes").show();
                var ctx2 = document.getElementById("chartTimes");

                @if ($live == true)
                    var times_data = JSON.parse(data.studytimes);
                @else
                    var times_data = data.studytimes;
                @endif

                var sorted_times = [];
                for (var time in times_data) {
                    sorted_times.push([time, times_data[time]]);
                }

                sorted_times.sort(function(a, b) {
                    return a[0] - b[0];
                });


                var len = sorted_times.length;
                var timeofday = new Array();
                var timecount = new Array();

                for(var i = 0; i < len; i++){
                    timeofday.push(sorted_times[i][0]);
                    timecount.push(sorted_times[i][1])
                }

                for(var x = 0; x < len; x++)
                {
                    if(timeofday[x] < 12){
                        timeofday[x] = timeofday[x] + "AM"; 
                    }
                    else if(timeofday[x] == 12){
                        timeofday[x] = timeofday[x] + "PM"; 
                    }
                    else{
                        timeofday[x] = (timeofday[x] - 12) + "AM";
                    }
                }

                new Chart(ctx2, {
                    type: 'bar',
                    data: {
                    labels: timeofday,
                    datasets: [
                        {
                            label: "Sessions Completed",
                            backgroundColor: "#3e95cd",
                            data: timecount
                        }
                    ]
                    },
                    options: {
                        title: {
                            display: true,
                            text: 'Most Active Time of Day'
                        },
                        scales: {
                            yAxes: [{
                                scaleLabel: {
                                    display: true,
                                    labelString: 'Number of Sessions',
                                    fontColor: '#9c9c9c'
                                }
                            }],
                            xAxes: [{
                                scaleLabel: {
                                    display: true,
                                    labelString: 'Time of Day',
                                    fontColor: '#9c9c9c'
                                }
                            }]
                        }
                    }
                });

                @if ($live == true)
                    createHiddenForm("studytimes", data['studytimes']);
                @endif
            }

            if (!(data['sessioncount'] == "N/A" || data['sessioncount'] == null))
            {
                $('#canvasSessionCount').show();

                @if ($live == true)
                    var sessioncounts = JSON.parse(data.sessioncount);
                @else
                    var sessioncounts = data.sessioncount;
                @endif

                var session_names = new Array();
                var session_counts = new Array();

                for (var val in sessioncounts)
                {
                    if (sessioncounts.hasOwnProperty(val))
                    {
                        session_names.push(val);
                        session_counts.push(sessioncounts[val]);
                    }
                }

                var ctx = document.getElementById("sessionCountChart").getContext('2d');

                var chart_data = {
                    labels: session_names,
                    datasets: [{
                        data: session_counts,
                        backgroundColor: [
                            "#00bcd4", "#2b8cba", "#3f51b5", "#9c27b0", "#e91e63", "#e65100", "#8bc34a", "#4caf50", "#797979", "#2196f3"
                        ],
                    }]
                } 

                var myPieChart = new Chart(ctx,{
                    type: 'pie',
                    data: chart_data,
                    options: {
                        title: {
                            display: true,
                            text: 'Number of Total Sessions per Module'
                        },
                        legend: {
                            display: true,
                            position: 'top'
                        }
                    }
                });

                @if ($live == true)
                    createHiddenForm("sessioncount", data['sessioncount']);
                @endif
            }
        }

        (function(){
            @if (!empty($schedule) && $live == true)
                var pred_list = {!! $grades !!};
            @elseif ($live == false)
                var data = {!! json_encode($data) !!}
                data = data.replace(/\\/g, "");
                data = JSON.parse(data)
                var pred_list = data['predictions']
            @endif



            for (var key in pred_list) {
                if (pred_list.hasOwnProperty(key)) {
                    if (pred_list[key][1] != null){
                        $('#predTable > tbody:last-child').append('<tr><td>' + pred_list[key][0] + '</td>' + '<td>' + pred_list[key][1] + '</td></tr>');
                        $('#predCard').show('slow');
                    } 
                }
            }

    

            @if ($live == true)
                createHiddenForm("predictions", JSON.stringify(pred_list));
            @endif
            
        })()

        function createHiddenForm(name, value)
        {
            var input = document.createElement("input");
            input.setAttribute("type", "hidden");
            input.setAttribute("name", name);
            input.setAttribute("value", value);

            document.getElementById("reportForm").appendChild(input);
        }

    });

</script>
@endsection