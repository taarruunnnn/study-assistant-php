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
                                        <input class="form-check-input paramCheckbox" type="checkbox" name="params[]" value="completed" id="chkCompleted" checked>
                                        <label class="form-check-label" for="chkCompleted">
                                            Completed Modules
                                        </label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input paramCheckbox" type="checkbox" name="params[]" value="moduleName" id="chkModuleName" checked>
                                        <label class="form-check-label" for="chkModuleName">
                                            Module Name
                                        </label>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input paramCheckbox" type="checkbox" name="params[]" value="failed" id="chkFailed" checked>
                                        <label class="form-check-label" for="chkFailed">
                                            Failed Modules
                                        </label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input paramCheckbox" type="checkbox" name="params[]" value="rating" id="chkRating" checked>
                                        <label class="form-check-label" for="chkRating">
                                            Rating
                                        </label>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    
                   
                    <button type="submit" class="btn btn-primary">Get Accuracy</button>
                </form>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-sm-6">
                <table class="table" id="accuracyTable" style="display:none;">
                    <thead>
                        <th scope="col">Algorithm</th>
                        <th scope="col">Accuracy</th>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Gaussian Naive-Bayes</td>
                            <td id="gnb"></td>
                        </tr>
                        <tr>
                            <td>Linear SVC</td>
                            <td id="lsvc"></td>
                        </tr>
                        <tr>
                            <td>KNeighbors Classifier</td>
                            <td id="knn"></td>
                        </tr>
                    </tbody>
                </table>
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

            $('#accuracyForm').submit(function(e){
                e.preventDefault();

                if ($('.paramCheckbox').filter(':checked').length == 0)
                {
                    alert("At least one data point should be checked.")
                    $('#accuracyTable').hide('slow')
                    return;
                }

                var form = $(this);

                $.ajax({
                    type: 'POST',
                    data: form.serialize(),
                    url: '{{ route('admin.predictions.accuracy') }}',
                    success: function(data){
                        console.log(data);
                        displayAccuracy(data);
                    },
                    error: function(message){
                        console.log('Failed '.message);
                    }
                });

            });

            function displayAccuracy(data)
            {
                $('#gnb').text(data.gnb);
                $('#lsvc').text(data.lsvc);
                $('#knn').text(data.knn);

                $('#accuracyTable').show('slow')
            }


        });
    </script>
@stop