@extends('layouts.master')

@section('title','Reports')

@section('content')

    <div class="container">
        <div class="row d-flex justify-content-center animated fadeIn">
            @if (! empty($schedule))
                <a href="{{ route('report.generate') }}" class="btn btn-primary m-2">Generate Report</a>
            @endif
            <button class="btn btn-primary m-2" type="button" data-toggle="modal" data-target="#searchModal">Search for Module</button>
        </div>
        @if (!( count($archived) == 0))
            <div class="row my-4 animated fadeIn">
                <div class="col text-center">
                    <button class="btn btn-danger" type="button" data-toggle="modal" data-target="#completedGradesModal">Grade Completed Modules</button>
                </div>
            </div>
        @endif
        <div class="row mt-3">
            <div class="col-md-6">
                <div class="card report-card border-light shadow-sm  h-100 animated fadeIn">
                    <div class="card-body">
                        <h5 class="card-title">Previous Reports</h5>
                        @if ($reports != null)
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Time</th>
                                        <th scope="col">Progress</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($reports as $key => $report)
                                        <tr class="clickable-row" data-href='{{ url("reports/view/{$report->id}") }}'>
                                            <td>{{ $key+1 }}</td>
                                            <td>{{ $report->created_at }}</td>
                                            <td>{{ $report->progress }}%</td>
                                        </tr> 
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <p class="text-center my-4">No Reports Available</p>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card report-card border-light shadow-sm  h-100 animated fadeIn">
                    <div class="card-body">
                        <h5 class="card-title">User Logs</h5>
                        @if (! empty($logs))
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Time</th>
                                        <th scope="col">Description</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($logs as $key => $log)
                                        <tr >
                                            <td>{{ $key+1 }}</td>
                                            <td>{{ $log->created_at }}</td>
                                            <td>{{ $log->description }}</td>
                                        </tr> 
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <p class="text-center my-4">No Logs Available</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="row my-5">
            <div class="col text-center">
                @if ($reports != null || $logs != null)
                    <button class="btn btn-danger" id="reportDelete">Delete Logs and Reports</button>
                    <p id="confirmDelete">Are you sure you want to <strong>Delete Current Schedule?</strong>
                        <br/>
                        <a class="btn btn-outline-primary btn-sm" href="{{ route('report.destroy') }}">Yes</a>
                        <a class="btn btn-outline-secondary btn-sm" id="cancelReportDelete">No</a>
                    </p>
                @else
                    <h4>No reports or logs to show</h4>
                @endif

            </div>
        </div>
    </div>

@endsection

@section('modal')
    <div class="modal fade" id="searchModal" tabindex="-1" role="dialog" aria-labelledby="searchModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="searchModalLabel">Search for Module</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col">
                            <form>
                                <div class="form-row aliggn-items-center">
                                    <div class="col-auto mb-3" id="typeahead-modules">
                                        <label for="textName" class="sr-only">Module Name</label>
                                        <input type="text" name="moduleName" id="textName" class="form-control typeahead" placeholder="Module Name">
                                    </div>
                                    <div class="col-auto">
                                        <button type="submit" class="btn btn-primary" id="btnSearch">Search</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col">
                                <h5 id="moduleName" class="my-3 text-center"></h5>
                                <div class="card-columns">
                                    <div class="card" id="hours">
                                        <div class="card-body">
                                            <h5 class="card-title">Average Hours</h5>
                                            <p class="card-text">The average student spends <span id="weekday-text" class="font-weight-bold"></span> hours per weekday and <span id="weekend-text" class="font-weight-bold"></span> hours per weekend day on this module</p>
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
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="completedGradesModal" tabindex="-1" role="dialog" aria-labelledby="completedGradesModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="row">
                        <div class="col">
                            <h5 class="text-primary text-center mb-3">Please grade these modules if results are available</h5>
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th style="width:60%">Module Name</th>
                                        <th style="width:40%">Grade</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <form action="{{ route('schedules.archive.update') }}" method="POST" id="gradesForm">
                                        @csrf
                                        @foreach ($archived as $key => $archive)
                                            <tr>
                                                <td>{{ $archive->name }}</td>
                                                <td>
                                                    <select class="form-control module-rating" name="module[{{$archive->id}}]">
                                                        <option value="null" @if ($archive->grade === null) selected="selected" @endif>None</option>
                                                        <option value="A+" @if ($archive->grade == "A+") selected="selected" @endif>A+</option>
                                                        <option value="A" @if ($archive->grade == "A") selected="selected" @endif>A</option>
                                                        <option value="A-" @if ($archive->grade == "A-") selected="selected" @endif>A-</option>
                                                        <option value="B+" @if ($archive->grade == "B+") selected="selected" @endif>B+</option>
                                                        <option value="B" @if ($archive->grade == "B") selected="selected" @endif>B</option>
                                                        <option value="B-" @if ($archive->grade == "B-") selected="selected" @endif>B-</option>
                                                        <option value="C+" @if ($archive->grade == "C+") selected="selected" @endif>C+</option>
                                                        <option value="C" @if ($archive->grade == "C") selected="selected" @endif>C</option>
                                                        <option value="C-" @if ($archive->grade == "C-") selected="selected" @endif>C-</option>
                                                        <option value="D" @if ($archive->grade == "D") selected="selected" @endif>D</option>
                                                        <option value="F" @if ($archive->grade == "F") selected="selected" @endif>F</option>
                                                        <option value="O" @if ($archive->grade == "O") selected="selected" @endif>Other</option>
                                                    </select>
                                                </td>
                                            </tr> 
                                        @endforeach
                                    </form>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="saveGrades">Save changes</button>
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

            
        $(".clickable-row").click(function(){
            window.location = $(this).data("href");
        });

        $( "#reportDelete" ).click(function(e) {
            e.preventDefault();
            $("#confirmDelete").slideToggle();
        });

        $('#cancelReportDelete').click(function(e) {
            e.preventDefault();
            $("#confirmDelete").slideUp();
        });

        $('#saveGrades').click(function(){
            $('form#gradesForm').submit();
        })

        $("#btnSearch").click(function(e){
            e.preventDefault();
            loadModuleData();
        });

        $('#searchModal').on('shown.bs.modal', function () {
            $('#textName').focus();
        })

        function loadModuleData()
        {
            var moduleName = $('#textName').val();

            $.ajax({
                type: 'POST',
                url: '{{ route('schedule.analyze') }}',
                data: {module: moduleName},
                success: function(data){
                    displayData(data, moduleName);
                },
                error: function(message){
                    console.log(message);
                }
            });

        }

        function displayData(data, moduleName)
        {
            $('#moduleName').text("Analysis for " + moduleName + " Based on All Students");

            $('#hours').css('display', 'none');
            $('#rating').css('display', 'none');
            $('#grades').css('display', 'none');
            $('#timeofday').css('display', 'none');
            $('#related').css('display', 'none');

            if (data['hours'].weekend_hours == "N/A" && data['hours'].weekday_hours == "N/A" && data['ratings'] == "N/A" && data['related'] == "N/A" && data['tod'] === "N/A")
            {
                $('#moduleName').text("No Analysis Data Available");
                return;
            }

            if (data['hours'].weekend_hours != "N/A" || data['hours'].weekday_hours != "N/A")
            { 
                $('#hours').css('display', 'inline-block');
                $('#weekday-text').text(data['hours'].weekday_hours);
                $('#weekend-text').text(data['hours'].weekend_hours);
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
                            "#29B6F6", "#EF5350", "#EC407A", "#9CCC65", "#FFCA28", "#BDBDBD", "#7E57C2", "#78909C", "#D4E157", "#FFA726", "#26A69A"
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
                } else {
                    $('#timeofday-text').text("Unspecified");
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
    });
</script>
@endsection