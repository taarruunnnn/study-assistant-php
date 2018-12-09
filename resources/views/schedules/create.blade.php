@extends('layouts.master')

@section('title','Create Schedule')

@section('content')

<div class="container-fluid">
    <div class="row justify-content-md-center">
        <div class="col-sm-9">
            <form method="POST" action="{{ route('schedules.store') }}">
                @csrf
        
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Duration : </label>
                
                    <div class="col-sm-6">
                        <div class="input-group input-daterange">
                            <input type="text" class="datepicker text-center form-control" id="start" name="start" placeholder="Start">
                            <div class="input-group-append">
                                <span class="input-group-text">to</span>
                            </div>
                            <input type="text" class="datepicker text-center form-control" id="end" name="end" placeholder="End">
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
                    <label class="col-sm-3 col-form-label">How many hours per day can you study? : </label>
                
                    <div class="col-sm-6">
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
                    <label class="col-sm-6 col-form-label">Enter Your Modules : </label>

                    <div class="col-sm-9">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">Module Name</th>
                                    <th scope="col">Rating</th>
                                    <th></th>
                                    <th scope="col">Avg Rating</th>
                                </tr>
                            </thead>
                            <tbody class="table-body">
                                <tr>
                                    <td>
                                        <div class="input-group">
                                            <input type="text" class="form-control module-names" id="module0" name="module[0]">
                                            <div class="input-group-append">
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-group">
                                            <select class="form-control" name="rating[0]">
                                                <option value="1">1</option>
                                                <option value="2">2</option>
                                                <option value="3">3</option>
                                                <option value="4">4</option>
                                                <option value="5">5</option>
                                                <option value="6">6</option>
                                                <option value="7">7</option>
                                                <option value="8">8</option>
                                                <option value="9">9</option>
                                                <option value="10">10</option>
                                            </select>
                                        </div>
                                    </td>
                                    <td>
                                        <button class="btn" id="btn-add"><i class="fas fa-plus"></i></button>
                                    </td>
                                    <td><div class="avg mt-2 text-center" id="avg0"><img src="{{ URL::asset( 'storage/ajax-loader.gif') }}" alt="Loader" class="ajax-loader"></div></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-sm-2">
                        <button class="btn btn-secondary" id="analyze">Analyze Modules</button>
                    </div>
                </div>
                
                <div class="form-group row">
                    <div class="col-sm-10">
                      <button type="submit" class="btn btn-primary float-right">Create Schedule</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('script')
    <script>
        $(document).ready(function(){   

            $("#analyze").click(analyzeModules);

            function addModuleInput(i){
                var moduleDiv = `<tr>
                                    <td>
                                        <div class="input-group">
                                            <input type="text" class="form-control module-names" id="module`+ i +`" name="module[`+ i +`]">
                                            <div class="input-group-append">
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-group">
                                            <select class="form-control" name="rating[`+ i +`]">
                                                <option value="1">1</option>
                                                <option value="2">2</option>
                                                <option value="3">3</option>
                                                <option value="4">4</option>
                                                <option value="5">5</option>
                                                <option value="6">6</option>
                                                <option value="7">7</option>
                                                <option value="8">8</option>
                                                <option value="9">9</option>
                                                <option value="10">10</option>
                                            </select>
                                        </div>
                                    </td>
                                    <td>
                                        <button class="btn btn-remove"><i class="fas fa-minus"></i></button>
                                    </td>
                                    <td><div class="avg mt-2 text-center" id="avg`+ i +`"><img src="{{ URL::asset( 'storage/ajax-loader.gif') }}" alt="Loader" class="ajax-loader"></div></td>
                                </tr>`
                
                return moduleDiv
            }
            
            var i = 1;
            var max_fields = 10;

            //append when add button is clicked
            $('#btn-add').click(function(e){
                e.preventDefault();

                if(i < max_fields){
                    $(".table-body").append(addModuleInput(i));
                    i++;
                }else{
                    $("#btn-add").prop("disabled", true);
                }
            });

            //remove when remove button is clicked
            $('.table-body').on('click','.btn-remove', function(e){
                e.preventDefault();
                $(this).parents('tr').remove();
                console.log('tr');
                i--;
            });

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });


            function analyzeModules(e)
            {
                e.preventDefault();

                $(".ajax-loader").show();

                var values = new Array();
                $(".module-names").each(function(){
                    values.push($(this).val());
                });

                $.ajax({
                    type: 'POST',
                    url: '{{ route('schedule.analyze') }}',
                    data: {modules: values},
                    success: function(analysis){
                        $(".ajax-loader").hide();
                        displayAnalysis(analysis);
                    },
                    error: function(message){
                        console.log(message);
                    }
                });
            }

            function displayAnalysis(analysis)
            {
                console.log(analysis);

                for (var key in analysis)
                {
                    if (analysis.hasOwnProperty(key))
                    {
                        var module = key;
                        var rating = analysis[key];

                        for (var x = 0; x <= i; x++)
                        {
                            var textBoxForModule = $("#module" + x);
                            if ($(textBoxForModule).val() == module)
                            {
                                var avg = '#avg'+x
                                $(avg).text(rating);
                            }
                        }
                    }
                }
            }
        });
    </script>
@endsection