@extends('layouts.master')

@section('title','Predictions')

@section('content')
    
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                    <h5 class="mb-3">Prediction Settings</h5>
                    <p>Below criteria are used as data points to make predictions in user reports. You can change them and see which combination yields the highest accuracy and save these settings accordingly.</p>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <form id="accuracyForm">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input paramCheckbox" type="checkbox" name="params[]" value="completed" id="chkCompleted">
                                        <label class="form-check-label" for="chkCompleted">
                                            Completed Modules
                                        </label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input paramCheckbox" type="checkbox" name="params[]" value="moduleName" id="chkModuleName">
                                        <label class="form-check-label" for="chkModuleName">
                                            Module Name
                                        </label>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input paramCheckbox" type="checkbox" name="params[]" value="failed" id="chkFailed">
                                        <label class="form-check-label" for="chkFailed">
                                            Failed Modules
                                        </label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input paramCheckbox" type="checkbox" name="params[]" value="rating" id="chkRating">
                                        <label class="form-check-label" for="chkRating">
                                            Rating
                                        </label>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    
                    <button type="submit" class="btn btn-primary">Check Accuracy</button>
                </form>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-sm-6">
                <table class="table table-bordered" id="accuracyTable">
                    <thead class="thead-dark">
                        <th scope="col">Algorithm</th>
                        <th scope="col" style="width:50%">Accuracy</th>
                    </thead>
                    <tbody>
                        <tr id="gnb">
                            <td>Gaussian Naive-Bayes</td>
                            <td id="gnbVal">-</td>
                        </tr>
                        <tr id="lsvc">
                            <td>Linear SVC</td>
                            <td id="lsvcVal">-</td>
                        </tr>
                        <tr id="knn">
                            <td>KNeighbors Classifier</td>
                            <td id="knnVal">-</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row my-4">
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="algorithm">Choose Algorthm</label>
                    <select class="form-control" id="algorithm">
                        <option value="" disabled selected>Select an algorithm</option>
                        <option value="gnb">Gaussian Naive-Bayes</option>
                        <option value="lsvc">Linear SVC</option>
                        <option value="knn">KNeighbors Classifier</option>
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
                    })
                    checkAccuracy();

                    $('#algorithm').val(prefs['algorithm']);

                    $('#'+prefs['algorithm']).css('font-weight', 'bold');

                })()
            @endif

            $('#accuracyForm').submit(function(e){
                e.preventDefault();
                checkAccuracy();
            });

            function checkAccuracy()
            {
                if ($('.paramCheckbox').filter(':checked').length == 0)
                {
                    alert("At least one data point should be checked.")
                    $('#gnbVal').text('-');
                    $('#lsvcVal').text('-');
                    $('#knnVal').text('-');
                    return;
                }

                var form = $('#accuracyForm');

                $.ajax({
                    type: 'POST',
                    data: form.serialize(),
                    url: '{{ route('admin.predictions.accuracy') }}',
                    success: function(data){
                        displayAccuracy(data);
                    },
                    error: function(message){
                        console.log('Failed '.message);
                    }
                });
            }

            $('#savePref').click(function(e){
                e.preventDefault();

                if ($('.paramCheckbox').filter(':checked').length == 0)
                {
                    alert("At least one data point should be checked.")
                    $('#gnbVal').text('-');
                    $('#lsvcVal').text('-');
                    $('#knnVal').text('-');
                    return;
                }

                var params = $('.paramCheckbox:checked').map(function(){
                    return $(this).val();
                }).get();

                params = JSON.stringify(params)

                var algorithm = $('#algorithm').val();

                var inputParams = document.createElement("input");
                inputParams.setAttribute("type", "hidden");
                inputParams.setAttribute("name", "params");
                inputParams.setAttribute("value", params);

                var inputAlgorithm = document.createElement("input");
                inputAlgorithm.setAttribute("type", "hidden");
                inputAlgorithm.setAttribute("name", "algorithm");
                inputAlgorithm.setAttribute("value", algorithm);

                document.getElementById("preferencesForm").appendChild(inputParams);
                document.getElementById("preferencesForm").appendChild(inputAlgorithm);

                $('#preferencesForm').submit();

            });

            function displayAccuracy(data)
            {
                $('#gnbVal').text(data.gnb);
                $('#lsvcVal').text(data.lsvc);
                $('#knnVal').text(data.knn);
            }

        });
    </script>
@stop