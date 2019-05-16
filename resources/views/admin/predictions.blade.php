@extends('layouts.master')

@section('title','Predictions')

@section('content')
    
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-6">
                <h5 class="mb-3">Prediction Settings</h5>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-5">
                <div class="row mb-4">
                    <div class="col">
                            <p>Below criteria are used as data points to make predictions in user reports. You can change them and see which combination yields the highest accuracy and save these settings accordingly.</p>
                        <form id="accuracyForm">
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <td>
                                            <div class="form-check">
                                                <input class="form-check-input paramCheckbox" type="checkbox" name="params[]" value="norm_comp" id="chkCompleted">
                                                <label class="form-check-label" for="chkCompleted">
                                                    Completed Modules
                                                </label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-check">
                                                <input class="form-check-input paramCheckbox" type="checkbox" name="params[]" value="name" id="chkName">
                                                <label class="form-check-label" for="chkName">
                                                    Module Name
                                                </label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="form-check">
                                                <input class="form-check-input paramCheckbox" type="checkbox" name="params[]" value="norm_fail" id="chkFailed">
                                                <label class="form-check-label" for="chkFailed">
                                                    Failed Modules
                                                </label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-check">
                                                <input class="form-check-input paramCheckbox" type="checkbox" name="params[]" value="rating" id="chkRating">
                                                <label class="form-check-label" for="chkRating">
                                                    Difficulty Rating
                                                </label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="form-check">
                                                <input class="form-check-input paramCheckbox" type="checkbox" name="params[]" value="university" id="chkUniversity">
                                                <label class="form-check-label" for="chkUniversity">
                                                    University
                                                </label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-check">
                                                <input class="form-check-input paramCheckbox" type="checkbox" name="params[]" value="major" id="chkMajor">
                                                <label class="form-check-label" for="chkMajor">
                                                    Major
                                                </label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="form-check">
                                                <input class="form-check-input paramCheckbox" type="checkbox" name="params[]" value="gender" id="chkGender">
                                                <label class="form-check-label" for="chkGender">
                                                    Gender
                                                </label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-check">
                                                <input class="form-check-input paramCheckbox" type="checkbox" name="params[]" value="birth" id="chkBirth">
                                                <label class="form-check-label" for="chkBirth">
                                                    Birth Year
                                                </label>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            
                            <button type="submit" class="btn btn-primary mb-3">Check Accuracy</button>
                        </form>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col">
                        <table class="table table-bordered" id="accuracyTable">
                            <thead class="thead-dark">
                                <th scope="col">Algorithm</th>
                                <th scope="col" style="width:50%">Accuracy</th>
                            </thead>
                            <tbody>
                                <tr id="gnb" style="color:#EC407A">
                                    <td>Gaussian Naive-Bayes</td>
                                    <td id="gnbVal">-</td>
                                </tr>
                                <tr id="knn" style="color:#29B6F6">
                                    <td>KNeighbors Classifier</td>
                                    <td id="knnVal">-</td>
                                </tr>
                                <tr id="rf" style="color:#f62929">
                                    <td>Random Forest</td>
                                    <td id="rfVal">-</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col">
                        <div class="form-group border p-4">
                            <h6>Prediction Threshold</h6>
                            <p>Select the minimum amount of completed modules which should be available for predictions to be enabled.</p>
                            <p>Currently Completed Modules: <strong>{{$completed}}</strong></p>
                            <input type="number" class="form-control w-25" id="threshold">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="row">
                    <div class="col">
                        <div class="card" style="display:none" id="canvasReport">
                            <div class="card-body">
                                <div class="px-4" id="reportWrapper">
                                    <canvas id="reportChart" width="200" height="200"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row my-4">
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="algorithm">Choose Algorthm</label>
                    <select class="form-control" id="algorithm">
                        <option value="" disabled selected>Select an algorithm</option>
                        <option value="gnb">Gaussian Naive-Bayes</option>
                        <option value="knn">KNeighbors Classifier</option>
                        <option value="rf">Random Forest</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary" id="savePref">Save Preferences</button>
                <form method="POST" action="{{ route('admin.predictions.save') }}" id="preferencesForm">
                    @csrf
                </form>
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

            var changed = false;

            
            window.onbeforeunload = function(){
                if (changed) {
                    $.ajax({
                        type: 'GET',
                        url: '{{ route('admin.predictions.reset') }}',
                        error: function(message){
                            toastr.error("Failed to reset prediction data");
                            console.log('Failed '.message);
                        }
                    });
                    return true;
                } else {
                    return null;
                }
            };
            
           

            @if(!(empty($json)))
                (function (){
                    var prefs = {!! $json !!};

                    var params = prefs['params'];
                    
                    $('.paramCheckbox').each(function(){
                        if (jQuery.inArray($(this).val(), params) !== -1)
                        {
                            $(this).prop('checked', true);
                        } else {
                            $(this).prop('checked', false);
                        }
                    });

                    checkAccuracy();

                    $('#algorithm').val(prefs['algorithm']);

                    $('#threshold').val(prefs['threshold']);

                    $('#'+prefs['algorithm']).css('font-weight', 'bold');

                })()
            @endif

            $('#accuracyForm').submit(function(e){
                e.preventDefault();
                checkAccuracy();
                changed = true;
            });

            function checkAccuracy()
            {
                if ($('.paramCheckbox').filter(':checked').length == 0)
                {
                    alert("At least one data point should be checked.")
                    $('#gnbVal').text('-');
                    $('#knnVal').text('-');
                    $('#rfVal').text('-');
                    return;
                }

                var form = $('#accuracyForm');

                $.ajax({
                    type: 'POST',
                    data: form.serialize(),
                    url: '{{ route('admin.predictions.accuracy') }}',
                    success: function(data){
                        displayPrecision(data);
                    },
                    error: function(message){
                        toastr.error("Failed to Check Accuracy");
                        console.log('Failed '.message);
                    }
                });
            }

            $('#savePref').click(function(e){
                e.preventDefault();
                changed = false;

                if ($('.paramCheckbox').filter(':checked').length == 0)
                {
                    alert("At least one data point should be checked.")
                    $('#gnbVal').text('-');
                    $('#knnVal').text('-');
                    $('#rfVal').text('-');
                    return;
                }

                var params = $('.paramCheckbox:checked').map(function(){
                    return $(this).val();
                }).get();

                params = JSON.stringify(params)

                var algorithm = $('#algorithm').val();
                var threshold = $('#threshold').val();

                var inputParams = document.createElement("input");
                inputParams.setAttribute("type", "hidden");
                inputParams.setAttribute("name", "params");
                inputParams.setAttribute("value", params);

                var inputAlgorithm = document.createElement("input");
                inputAlgorithm.setAttribute("type", "hidden");
                inputAlgorithm.setAttribute("name", "algorithm");
                inputAlgorithm.setAttribute("value", algorithm);

                var inputThreshold = document.createElement("input");
                inputThreshold.setAttribute("type", "hidden");
                inputThreshold.setAttribute("name", "threshold");
                inputThreshold.setAttribute("value", threshold);

                document.getElementById("preferencesForm").appendChild(inputParams);
                document.getElementById("preferencesForm").appendChild(inputAlgorithm);
                document.getElementById("preferencesForm").appendChild(inputThreshold);

                $('#preferencesForm').submit();

            });

            function displayPrecision(data)
            {
                $('#gnbVal').text(data.gnb);
                $('#knnVal').text(data.knn);
                $('#rfVal').text(data.rf);

                if (!(data['reports'] == "N/A" || data['reports'] == null))
                {
                    $('#reportChart').remove();
                    $('#reportWrapper').append('<canvas id="reportChart" height="200"></canvas>')

                    $("#canvasReport").show();
                    var ctx = document.getElementById("reportChart");

                    // GAUSSIAN NB DATA
                    var gnb_data = JSON.parse(data.reports.gnb_report);
                    gnb_data = gnb_data['precision'];

                    var gnb_rates = [];
                    for (var rate in gnb_data) {
                        gnb_rates.push([rate, gnb_data[rate]]);
                    }

                    var len = gnb_rates.length;
                    var gnb_key = new Array();
                    var gnb_value = new Array();

                    for(var i = 0; i < len; i++){
                        gnb_key.push(gnb_rates[i][0]);
                        gnb_value.push(gnb_rates[i][1])
                    }


                    //KNeighbors DATA
                    var knn_data = JSON.parse(data.reports.knn_report);
                    knn_data = knn_data['precision'];

                    var knn_rates = [];
                    for (var rate in knn_data) {
                        knn_rates.push([rate, knn_data[rate]]);
                    }

                    var len = knn_rates.length;
                    var knn_key = new Array();
                    var knn_value = new Array();

                    for(var i = 0; i < len; i++){
                        knn_key.push(knn_rates[i][0]);
                        knn_value.push(knn_rates[i][1])
                    }

                    //RandomForest DATA
                    var rf_data = JSON.parse(data.reports.rf_report);
                    rf_data = rf_data['precision'];

                    var rf_rates = [];
                    for (var rate in rf_data) {
                        rf_rates.push([rate, rf_data[rate]]);
                    }

                    var len = rf_rates.length;
                    var rf_key = new Array();
                    var rf_value = new Array();

                    for(var i = 0; i < len; i++){
                        rf_key.push(rf_rates[i][0]);
                        rf_value.push(rf_rates[i][1])
                    }


                    new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: gnb_key,
                            datasets: [
                                {
                                    label: "Gaussian NB",
                                    borderColor: "#EC407A",
                                    borderWidth: 2,
                                    data: gnb_value,
                                    fill: false
                                },
                                {
                                    label: "KNeighbors",
                                    borderColor: "#29B6F6",
                                    borderWidth: 2,
                                    data: knn_value,
                                    fill: false
                                },
                                {
                                    label: "Random Forest",
                                    borderColor: "#f62929",
                                    borderWidth: 2,
                                    data: rf_value,
                                    fill: false
                                },
                            ]
                        },
                        options: {
                            title: {
                                display: true,
                                text: 'Precision Scores'
                            },
                            scales: {
                                yAxes: [{
                                    scaleLabel: {
                                        display: true,
                                        labelString: 'Precision',
                                        fontColor: '#9c9c9c'
                                    }
                                }],
                                xAxes: [{
                                    scaleLabel: {
                                        display: true,
                                        labelString: 'Grade',
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
@stop