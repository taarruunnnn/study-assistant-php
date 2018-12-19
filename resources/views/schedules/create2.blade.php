@extends('layouts.master')

@section('title','Create Schedule')

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-sm-9">
                <div class="row">
                    <form class="form-inline">
                        <div class="form-group mx-sm-3 mb-2">
                            <input type="text" class="form-control" id="module-name" placeholder="Module Name">
                        </div>
                        <button type="submit" class="btn btn-primary mb-2 mr-2" id="analyze">Analyze</button>
                        <button type="submit" class="btn btn-primary mb-2" id="save">Save</button>
                    </form>
                </div>
                <div class="row my-4">
                    <h3 id="module-header"></h3>
                </div>
                <div class="row mt-3">
                    <div class="card-deck analyzed-data" style="display:none;">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Average Hours</h5>
                                <p class="card-text">The average student spends <span id="hours" class="font-weight-bold"></span> hours per day on this module</p>
                            </div>
                        </div>
                        <div class="card" style="width: 18rem;">
                            <div class="card-body">
                                <h5 class="card-title">Average Rating</h5>
                                <p class="card-text">The average student gave this module a rating of <span id="rating" class="font-weight-bold"></span></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-3">
                        <canvas id="myChart" width="400" height="400"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="row">
                    <h5>Selected Modules</h5>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.3/Chart.bundle.js"></script>
    <script>
        $(document).ready(function(){

             $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            

            
            
            $("#analyze").click(function(e){
                e.preventDefault();
                $('.analyzed-data').hide();
                analyze();
            });

            function analyze()
            {
                var moduleName = $("#module-name").val();
                $('#module-header').html("Analysis for " + moduleName);

                $.ajax({
                    type: 'POST',
                    url: '{{ route('schedule.analyze2') }}',
                    data: {module: moduleName},
                    success: function(data){
                        displayAnalysis(data);
                    },
                    error: function(message){
                        console.log(message);
                    }
                });
            }

            function displayAnalysis(data)
            {
                console.log(data);
                if (data.hasOwnProperty('hours'))
                {
                    $('.analyzed-data').show("slow");
                    $('#hours').text(data['hours']);
                }

                if (data.hasOwnProperty('ratings'))
                {
                    $('.analyzed-data').show("slow");
                    $('#rating').text(data['ratings']);
                }
                
                if (data.hasOwnProperty('grades'))
                {
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
                                'rgba(255, 99, 132, 0.2)',
                                'rgba(54, 162, 235, 0.2)',
                                'rgba(255, 206, 86, 0.2)',
                                'rgba(75, 192, 192, 0.2)',
                                'rgba(153, 102, 255, 0.2)',
                                'rgba(255, 159, 64, 0.2)'
                            ],
                        }]
                    } 

                    var myPieChart = new Chart(ctx,{
                        type: 'pie',
                        data: chart_data,
                    });

                    
                }
            }
        });
    </script>
@endsection