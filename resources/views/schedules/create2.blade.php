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
                <div class="row">
                    <div class="analyzed-data">

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
    <script>
        $(document).ready(function(){
            var moduleName = $("#module-name").val();
            
            $("#analyze").click(analyze);

            function analyze()
            {
                $.ajax({
                    type: 'POST',
                    url: '{{ route('schedule.analyze') }}',
                    data: {module: moduleName},
                    success: function(data){
                        displayAnalysis(data);
                    },
                    error: function(message){
                        // failedAjax();
                        console.log(message);
                    }
                });
            }

            function displayAnalysis(data)
            {

            }
        });
    </script>
@endsection