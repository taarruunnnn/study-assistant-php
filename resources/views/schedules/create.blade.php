@extends('layouts.master')

@section('title','Create Schedule')

@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-8 order-12 order-lg-0">
                <div class="row">
                    <div class="col text-center">
                            <p>Enter Module Name and Difficulty</p>
                    </div>
                </div>
                <div class="row d-flex justify-content-center">
                    <div class="col-md-8">
                        <div class="row">
                                <div class="col">
                                    <form class="form-inline d-flex justify-content-center">
                                        <div class="form-group mb-2" id="typeahead-modules">
                                            <input type="text" class="form-control typeahead" id="module-name" placeholder="Module Name" required>
                                        </div>
                                        <div class="form-group ml-2">
                                            <button type="submit" class="btn btn-secondary mb-2 mr-2" id="analyze">Analyze</button>
                                        </div>
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-primary mb-2" id="btn-add">Add</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="row d-flex justify-content-center">
                                <div class="col-md-8">
                                    <div class="form-group mt-1">
                                        <input type="range" class="custom-range" name="rating" id="module-rating" min="1" max="10" value="1">
                                        <label for="module-rating" class="float-left text-muted">Very Easy</label>
                                        <label for="module-rating" class="float-right text-muted">Very Hard</label>
                                        <label for="module-rating" class="text-center d-block text-primary" id="range-selected"></label>
                                    </div>
                                </div>
                            </div>
                    </div>
                </div>
                <div class="row my-4 ml-4">
                    <h3 id="module-header"></h3>
                </div>
                <div class="card-columns">
                    <div class="card border-light shadow-sm" id="hours">
                        <div class="card-body">
                            <h5 class="card-title">Average Hours</h5>
                            <p class="card-text">The average student spends <span id="weekday-text" class="font-weight-bold"></span> hours per weekday and <span id="weekend-text" class="font-weight-bold"></span> hours per weekend day on this module</p>
                        </div>
                    </div>
                    <div class="card border-light shadow-sm" id="rating">
                        <div class="card-body">
                            <h5 class="card-title">Average Rating</h5>
                            <p class="card-text">The average student gave this module a rating of <span id="rating-text" class="font-weight-bold"></span></p>
                        </div>
                    </div>
                    <div class="card border-light shadow-sm" id="timeofday">
                        <div class="card-body">
                            <h5 class="card-title">Time of Day</h5>
                            <p class="card-text">Most students study this subject during <span id="timeofday-text" class="font-weight-bold"></span></p>
                        </div>
                    </div>
                    <div class="card border-light shadow-sm" id="grades">
                        <div class="card-body" id="grades-container">
                            <canvas id="myChart" width="300" height="432"></canvas>
                        </div>
                    </div>
                    <div class="card border-light shadow-sm" id="related">
                        <div class="card-body">
                            <h5 class="card-title">Related Modules</h5>
                            <p class="card-text">Most students who have studied for <span class="module-name" class="font-weight-bold"></span> have also enrolled in</p>
                            <ul id="related-modules" class="list-group list-group-flush">
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="row d-flex justify-content-center mb-3">
                    <div class="card border-light shadow-sm">
                        <div class="card-body">
                            <h4 class="text-primary">Schedule Details</h4>
                            <hr/>
                            <form method="POST" action="{{ route('schedules.store') }}">
                                @csrf
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Duration</label>
                                
                                    <div class="col-sm-9">
                                        <div class="input-group input-daterange">
                                            <input type="text" class="datepicker text-center form-control" id="start" name="start" placeholder="Start" required>
                                            <div class="input-group-append">
                                                <span class="input-group-text">to</span>
                                            </div>
                                            <input type="text" class="datepicker text-center form-control" id="end" name="end" placeholder="End" required>
                                        </div>
                
                                        @if ($errors->has('start'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('start') }}</strong>
                                            </span>
                                        @endif
                                        @if ($errors->has('end'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('end') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Study Hours Per Day</label>
                                
                                    <div class="col-sm-9">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <select class="form-control" name="weekdays" id="weekdays">
                                                    <option value="2">2</option>
                                                    <option value="4">4</option>
                                                    <option value="6">6</option>
                                                    <option value="8">8</option>
                                                    <option value="10">10</option>
                                                    <option value="12">12</option>
                                                </select>
                                                <label for="weekdays">On Weekdays</label>
                                            </div>
                                            <div class="col-sm-6">
                                                    <select class="form-control" name="weekends" id="weekends">
                                                        <option value="2">2</option>
                                                        <option value="4">4</option>
                                                        <option value="6">6</option>
                                                        <option value="8">8</option>
                                                        <option value="10">10</option>
                                                        <option value="12">12</option>
                                                    </select>
                                                    <label for="weekends">On Weekdends</label>
                                                </div>
                                        </div>
                                        
                                            
                
                                        @if ($errors->has('weekdays'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('weekday') }}</strong>
                                            </span>
                                        @endif
                                        @if ($errors->has('weekends'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('weekends') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="d-flex justify-content-center w-100">
                                        <h5>Module List</h5>
                                    </div>
                                    <table id="module-list" class="table"></table>
                                </div>
                                <div class="form-group row">
                                    <button type="submit" class="btn btn-primary ml-3" id="btn-submit" disabled>Create Schedule</button>
                                </div>
                            </form>
                        </div>

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

            $('#start').datepicker({
                maxViewMode: 'years',
                format: "yyyy-mm-dd",
                todayHighlight: true,
                autoclose: true,
                startDate: "today",
                weekStart: 1
            }).on('changeDate', function(selected){
                var minDate = new Date(selected.date.valueOf());
                $('#end').datepicker('setStartDate', minDate);

                var maxDate = new Date(selected.date.valueOf());
                maxDate = new Date(maxDate.setMonth(maxDate.getMonth()+11));
                $('#end').datepicker('setEndDate', maxDate);
            });

            $('#end').datepicker({
                maxViewMode: 'years',
                format: "yyyy-mm-dd",
                todayHighlight: true,
                autoclose: true,
                startDate: "today",
                weekStart: 1
            }).on('changeDate', function (selected) {
                var maxDate = new Date(selected.date.valueOf());
                $('#start').datepicker('setEndDate', maxDate);
            });

            
            $("#analyze").click(function(e){
                e.preventDefault();
                $('.analyzed-data').hide();
                analyze();
            });

            function analyze()
            {
                var moduleName = $("#module-name").val();

                if (moduleName.trim() === "" || moduleName === null){
                    return false;
                }

                $.ajax({
                    type: 'POST',
                    url: '{{ route('schedule.analyze') }}',
                    data: {module: moduleName},
                    success: function(data){
                        displayAnalysis(data);
                    },
                    error: function(message){
                        console.log(message);
                        displayAnalysis(null);
                    }
                });
            }

            function displayAnalysis(data)
            {
                $('#hours').css('display', 'none');
                $('#rating').css('display', 'none');
                $('#grades').css('display', 'none');
                $('#timeofday').css('display', 'none');
                $('#related').css('display', 'none');

                if (data['hours'].weekend_hours == "N/A" && data['hours'].weekday_hours == "N/A" && data['ratings'] == "N/A" && data['related'] == "N/A" && data['tod'] === "N/A")
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
                    var ctx = document.getElementById("myChart").getContext('2d');

                    var chart_data = {
                        labels: grades_names,
                        datasets: [{
                            data: grades_grades,
                            backgroundColor: [
                                "#4783C2", 
                                "#2B2673", 
                                "#2B8267", 
                                "#183749", 
                                "#9D3463", 
                                "#8D349D", 
                                "#3A349D", 
                                "#86A136", 
                                "#A45837", 
                                "#B4443C"
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
                    else if(tod = 12)
                    {
                        // PM
                        $('#timeofday-text').text(tod + "PM");
                    }
                    else
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

            function addModule(i)
            {
                var module_list = `
                <tr>
                    <td>
                        <input type="text" class="form-control module-names-list" id="module`+ i +`" name="module[`+ i +`]">
                    </td>
                    <td class="rating-col">
                        <input type="number" class="form-control" id="rating`+ i +`" name="rating[`+ i +`]" min="1" max="10">
                    </td>
                    <td>
                        <button class="btn btn-outline-danger btn-remove" type="button"><i class="fas fa-minus"></i></button>
                    </td>
                </tr>
                `

                return module_list
            }


            (function ()
            {
                var i = 0;
                var max_fields = 10;

                $('#btn-add').click(function(e){
                    e.preventDefault();
                    var moduleName = $("#module-name").val();
                    var moduleRating = $("#module-rating").val();

                    if (moduleName.trim() === "" || moduleName === null){
                        return false;
                    }

                    if(i < max_fields)
                    {   
                        $("#module-list").append(addModule(i));

                        $("#module"+ i ).val(moduleName);
                        $("#rating"+ i ).val(moduleRating);
                        $("#module-name").val("");
                        $("#module-rating").prop('selectedIndex',0);
                        $("#btn-submit").prop("disabled", false);
                        $('.typeahead').typeahead('val', '');
                        i++;
                    }else{
                        $("#btn-add").prop("disabled", true);
                    }

                    $('#module-rating').val(1);
                    $('#range-selected').text(1);
                });

                $('#module-list').on('click','.btn-remove', function(e){
                    e.preventDefault();
                    $(this).parents('tr').remove();
                    console.log('tr');
                    i--;

                    if(i < max_fields)
                    {
                        $("#btn-add").prop("disabled", false);
                    }

                    if(i < 1)
                    {
                        $("#btn-submit").prop("disabled", true);
                    }
                });

                $('#range-selected').text($('#module-rating').val());

                $('#module-rating').on('input change', function() {
                    $('#range-selected').text($('#module-rating').val());
                })
            })();
            
        });
    </script>
@endsection