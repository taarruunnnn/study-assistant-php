@extends('layouts.master')

@section('title','Dashboard')

@section('content')

<div class="container-fluid" id="dash">
    <div class="row ml-2">
        <div class="col-md-3">
            <div class="card h-100 animated fadeIn">
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
            <div class="card h-100 animated fadeIn">
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
            <div class="card h-100 animated fadeIn">
                <div class="card-body">
                    <h5 class="card-title dash-title text-center">Modules Summary</h5>
                    <hr/>
                    @if (! empty($schedule))
                        <canvas id="pie-chart" width="250" height="350"></canvas>
                        <p class="card-subtitle">Click on a module in the chart to learn more about it.</p>
                    @else
                        <p>No Schedule data to display</p>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card h-100 animated fadeIn">
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
    <div class="row my-3 ml-2">
        @if (! empty($schedule))
            <div class="col-md-9">
                <div class="card animated fadeIn">
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
                <div class="card animated fadeIn">
                    <div class="card-body">
                        <h5 class="card-title dash-title text-center">Schedule Overview</h5>
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

@if (isset($schedule))
    <div class="modal fade" id="analysisModal" tabindex="-1" role="dialog" aria-labelledby="analysisModuleLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="analysisModuleLabel">Module Analysis</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card-columns">
                        <div class="card" id="hours">
                            <div class="card-body">
                                <h5 class="card-title">Average Hours</h5>
                                <p class="card-text">The average student spends <span id="hours-text" class="font-weight-bold"></span> hours per day on this module</p>
                            </div>
                        </div>
                        <div class="card" id="rating">
                            <div class="card-body">
                                <h5 class="card-title">Average Rating</h5>
                                <p class="card-text">The average student gave this module a rating of <span id="rating-text" class="font-weight-bold"></span></p>
                            </div>
                        </div>
                        <div class="card" id="timeofday">
                            <div class="card-body">
                                <h5 class="card-title">Time of Day</h5>
                                <p class="card-text">Most students study this subject during <span id="timeofday-text" class="font-weight-bold"></span></p>
                            </div>
                        </div>
                        <div class="card" id="grades">
                            <div class="card-body" id="grades-container">
                                <canvas id="myChart" width="300" height="432"></canvas>
                            </div>
                        </div>
                        <div class="card" id="related">
                            <div class="card-body">
                                <h5 class="card-title">Related Modules</h5>
                                <p class="card-text">Most students who have studied for <span class="module-name" class="font-weight-bold"></span> have also enrolled in</p>
                                <ul id="related-modules" class="list-group list-group-flush">
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endif

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
                (function ()
                {
                    var modules = new Array();
                    var ratings = new Array();
                    @foreach ($modules as $module)
                        modules.push('{{$module->name}}')
                        ratings.push('{{$module->rating}}')
                    @endforeach

                    var canvasModules = document.getElementById("pie-chart");
                    var ctxModules = canvasModules.getContext("2d");

                    var chartModules = new Chart(ctxModules, {
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
                            },
                            events: ['mousemove'],
                            onHover: (event, chartElement) => {
                                event.target.style.cursor = chartElement[0] ? 'pointer' : 'default';
                                }
                        }
                    });

                    canvasModules.onclick = function(evt){
                        var activePoints = chartModules.getElementsAtEvent(evt);
                        if(activePoints[0]){
                            var chartData = activePoints[0]['_chart'].config.data;
                            var idx = activePoints[0]['_index'];
                            var label = chartData.labels[idx];
                            moduleData(label);
                        }
                    }

                    function moduleData(label)
                    {
                        var moduleName = label;

                            $.ajax({
                                type: 'POST',
                                url: '{{ route('schedule.analyze') }}',
                                data: {module: moduleName},
                                success: function(data){
                                    showModal(data, moduleName);
                                    console.log(data);
                                },
                                error: function(message){
                                    console.log(message);
                                }
                            });
                    }
                })();
            @endif
            

            function showModal(data, moduleName)
            {
                $('#analysisModuleLabel').text("Analysis for " + moduleName + " Based on All Students");
                $('#analysisModal').modal();

                $('#hours').css('display', 'none');
                $('#rating').css('display', 'none');
                $('#grades').css('display', 'none');
                $('#timeofday').css('display', 'none');
                $('#related').css('display', 'none');

                if (data['grades'] == "N/A" && data['hours'] == "N/A" && data['ratings'] == "N/A" && data['related'] == "N/A" && data['tod'] === "N/A")
                {
                    $('#module-header').html("No Analysis Data Available");
                    return;
                }
                else
                {
                    var moduleName = $("#module-name").val();
                    $('#module-header').html("Analysis for " + moduleName);
                    $('.module-name').html(moduleName);
                    
                }

                console.log(data);

                if (data['hours'] != "N/A")
                { 
                    $('#hours').css('display', 'inline-block');
                    $('#hours-text').text(data['hours']);
                }

                if (data['ratings'] != "N/A")
                {
                    $('#rating').css('display', 'inline-block');
                    $('#rating-text').text(data['ratings']);
                }
                
                if (data['grades'] != "N/A")
                {
                    $("#grades").css('display', 'inline-block');

                    $('#myChart').remove();
                    $('#grades-container').append('<canvas id="myChart" width="300" height="432"></canvas>')
                    
                    grades = JSON.parse(data.grades).grade;

                    grades_names = new Array();
                    grades_grades = new Array();

                    for (var key in grades) {
                        if (grades.hasOwnProperty(key)) {
                            grades_names.push(key);
                            grades_grades.push(grades[key])
                        }
                    }

                    console.log(grades_grades);
                    var ctx = document.getElementById("myChart").getContext('2d');

                    var chart_data = {
                        labels: grades_names,
                        datasets: [{
                            data: grades_grades,
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
                                text: 'Grades Obtained for Module'
                            }
                        }
                    });
                }

                if (data['tod'] != "N/A")
                {
                    $('#timeofday').css('display', 'inline-block');

                    var tod = data['tod']
                    if(tod < 12)
                    {
                        // AM
                        $('#timeofday-text').text(tod + "AM");
                    }
                    else if(tod >= 12)
                    {
                        tod = tod - 12;
                        // PM
                        $('#timeofday-text').text(tod + "PM");
                    }
                    
                }

                if (data['related'] != "N/A")
                {
                    $('#related').css('display', 'inline-block');

                    related_modules = JSON.parse(data.related)

                    var modules_array = []

                    for(var x in related_modules)
                    {
                        modules_array.push(related_modules[x]);
                    }

                    var list = $("#related-modules");
                    list.html("");

                    $.each(modules_array, function(i)
                    {
                        list.append('<li class="list-group-item">' + modules_array[i] + '</li>')
                    });
                    
                }
            }

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

                
            (function() {
                var time = moment().format('HH:mm:ss');
                var date = moment().format('MMMM Do YYYY')
                $('#time').html(time);
                $('#date').html(date);
                setTimeout(displayTime, 1000);
            })();

        })
    </script>
@endsection
