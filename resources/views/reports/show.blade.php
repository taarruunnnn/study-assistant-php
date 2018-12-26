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
                            <p class="text-dark">Number of Modules in this Schedule : 
                                <span class="text-primary">
                                    @if (! empty($modules))
                                        {{ $modules->count() }}
                                    @else
                                        0
                                    @endif
                                </span>
                            </p>

                            <p class="text-dark">Number of Sessions Completed : 
                                <span class="text-primary">    
                                    @if (! empty($sessions))
                                        {{ $sessions->where('status', 'completed')->count() }}
                                    @else
                                        0
                                    @endif
                                </span>
                            </p>
                            
                            <p class="text-dark">Number of Sessions Missed : 
                                <span class="text-primary">
                                    @if (! empty($modules))
                                        {{ $sessions->where('status', 'failed')->count() }}
                                    @else
                                        0
                                    @endif
                                </span>
                            </p>

                            <p class="text-dark">Number of Sessions to Complete : 
                                <span class="text-primary">
                                    @if (! empty($modules))
                                        {{ $sessions->where('status', 'incomplete')->count() }}
                                    @else
                                        0
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
            </div>
            <div class="col-sm-6">
                <div class="card">
                    <div class="card-body">
                        <canvas id="myChart" width="400" height="220"></canvas>
                    </div>
                </div>
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
                var ctx = document.getElementById("myChart");

                completed_sessions = JSON.parse(data.sessions).Completed;
                total_sessions = JSON.parse(data.sessions).Total;

                months = new Array();
                completed_count = new Array();
                total_count = new Array();

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

                console.log(months)
                console.log(completed_count)

                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: months,
                        datasets: [{
                            data: completed_count,
                            label: "Completed Sessions",
                            borderColor: "#8e5ea2",
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
                        }
                    }
                })
            }
        }


    });

</script>
@endsection