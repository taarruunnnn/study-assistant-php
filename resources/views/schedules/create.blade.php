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
                            <input type="text" class="datepicker text-center form-control" id="start" name="start">
                            <div class="input-group-append">
                                <span class="input-group-text">to</span>
                            </div>
                            <input type="text" class="datepicker text-center form-control" id="end" name="end">
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
                        <div class="input-group">
                            <input type="number" class="form-control" id="weekdays" name="weekdays" placeholder="Weekdays">
                            <input type="number" class="form-control" id="weekends" name="weekends" placeholder="Weekends">
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
                    <label class="col-sm-2 col-form-label">Enter Your Modules : </label>

                    <div class="col-sm-8">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">Module Name</th>
                                    <th scope="col">Rating</th>
                                </tr>
                            </thead>
                            <tbody class="table-body">
                                <tr>
                                    <td>
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="module0" name="module[0]">
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
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>


                
                <div class="form-group row">
                    <div class="col-sm-10">
                      <button type="submit" class="btn btn-primary">Create Schedule</button>
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

            function addModuleInput(i){
                var moduleDiv = `<tr>
                                    <td>
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="module`+ i +`" name="module[`+ i +`]">
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
        });
    </script>
@endsection