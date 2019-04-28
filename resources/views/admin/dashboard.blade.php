@extends('layouts.master')

@section('title','Dashboard')

@section('content')

<div class="container-fluid mb-4" id="overview">
    <div class="row">
        <div class="col">
            <div class="alert alert-danger text-center" role="alert" style="display:none" id="retrain-warning">
                <a href="{{ route('admin.predictions') }}">Prediction models haven't been trained. Please save your prediction preferences to initiate training.</a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col text-center text-primary"><h5>System Overview</h5></div>
    </div>
    <div class="row my-2">
        <div class="col-sm-3">
            <div class="card border-light shadow-sm  h-100 animated fadeIn">
                <div class="card-body text-center">
                    <i class="fas fa-users"></i>
                    <h5 class="card-title mt-2 mb-0 display-4" id="user-count">{{ $users }}</h5>
                    <p class="card-text">Total Users</p>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="card border-light shadow-sm  h-100 animated fadeIn">
                <div class="card-body text-center">
                    <i class="fas fa-calendar"></i>
                    <h5 class="card-title mt-2 mb-0 display-4" id="schedule-count">{{ $schedules }}</h5>
                    <p class="card-text">Total Schedules</p>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="card border-light shadow-sm  h-100 animated fadeIn">
                <div class="card-body text-center">
                    <i class="fas fa-book"></i>
                    <h5 class="card-title mt-2 mb-0 display-4" id="module-count">{{ $modules }}</h5>
                    <p class="card-text">Total Modules</p>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="card border-light shadow-sm  h-100 animated fadeIn">
                <div class="card-body text-center">
                    <i class="fas fa-trophy"></i>
                    <h5 class="card-title mt-2 mb-0 display-4" id="completed-module-count">{{ $completed }}</h5>
                    <p class="card-text">Completed Modules</p>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-sm-6">
            <div class="card border-light shadow-sm  h-100 animated fadeIn">
                <div class="card-body">
                    <h5 class="card-title text-center text-primary">User Statistics</h5>
                    <table class="table">
                        <tbody>
                            <tr id="weekday-row" style="display:none;">
                                <td>Average Number of Weekday Study Hours</td>
                                <td id="weekday"></td>
                            </tr>
                            <tr id="weekend-row" style="display:none;">
                                <td>Average Number of Weekend Study Hours</td>
                                <td id="weekend"></td>
                            </tr>
                            <tr id="modules-row" style="display:none;">
                                <td>Average Number of Modules per User</td>
                                <td id="modules"></td>
                            </tr>
                            <tr id="completed-row" style="display:none;">
                                <td>Number of Completed Sessions</td>
                                <td id="completed"></td>
                            </tr>
                            <tr id="incomplete-row" style="display:none;">
                                <td>Number of Incomplete Sessions</td>
                                <td id="incomplete"></td>
                            </tr>
                            <tr id="failed-row" style="display:none;">
                                <td>Number of Failed Sessions</td>
                                <td id="failed"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-light shadow-sm  h-100 animated fadeIn" id="monthsDiv" style="display:none;">
                <div class="card-body">
                    <h5 class="card-title dash-title text-center text-primary">User Growth</h5>
                    <hr>
                    <div>
                        <canvas id="chartMonths" width="500" height="265"></canvas>
                    </div>
                </div>
            </div>
        </div>
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

            (function()
            {
                $.ajax({
                    type: 'GET',
                    url: '{{ route('admin.analyze') }}',
                    success: function(data){
                        if (data['init'] == "Failed") {
                            $('#retrain-warning').show('slow');
                        }
                        displayAnalysis(data);
                    },
                    error: function(message){
                        console.log('Failed '.message);
                    }
                });
            })()

            function displayAnalysis(data)
            {
                if (data['hours'] != null)
                {
                    $('#weekend-row').show('slow');
                    var weekend = data['hours'].weekend;
                    $('#weekend').text(weekend);

                    $('#weekday-row').show('slow');
                    var weekday = data['hours'].weekday;
                    $('#weekday').text(weekday);
                }

                if (data['modules'] != null)
                {
                    $('#modules-row').show('slow');
                    var modules = data['modules'];
                    $('#modules').text(modules);
                }

                if (data['sessions'] != null)
                {
                    $('#completed-row').show('slow');
                    var completed = JSON.parse(data['sessions']).completed;
                    $('#completed').text(completed);

                    $('#incomplete-row').show('slow');
                    var incomplete = JSON.parse(data['sessions']).incomplete;
                    $('#incomplete').text(incomplete);

                    $('#failed-row').show('slow');
                    var failed = JSON.parse(data['sessions']).failed;
                    $('#failed').text(failed);
                }

                if (data['months'] != null)
                {
                    $("#monthsDiv").show();

                    var ctx = document.getElementById("chartMonths");

    
                    var months = JSON.parse(data.months);


                    var month_name = new Array();
                    var month_count = new Array();

                    for (var month in months) {
                        if (months.hasOwnProperty(month)) {
                            month_name.push(month);
                            month_count.push(months[month])
                        }
                    }


                    new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: month_name,
                            datasets: [{
                                data: month_count,
                                label: "New Users",
                                borderColor: "#2196F3",
                                fill: false
                            }]
                        },
                        options: {
                            scales: {
                                yAxes: [{
                                    scaleLabel: {
                                        display: true,
                                        labelString: 'New Users',
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
        });
    </script>
@endsection
