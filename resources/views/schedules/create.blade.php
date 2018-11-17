@extends('layouts.master')

@section('title','Create Schedule')

@section('content')

<div class="container">
    <div class="row justify-content-md-center">
        <div class="col-sm-9">
            <form method="POST" action="{{ route('schedules.store') }}">
                @csrf
            
                <div class="form-group row">
                    <label for="name" class="col-sm-2 col-form-label">Schedule Name :</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="name"  name="name" value="Default" required />
                    </div>
                </div>
        

                <div class="form-group row">
                    <label for="birth" class="col-sm-2 col-form-label">Duration : </label>
                
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
                    <label class="col-sm-2 col-form-label">Enter Your Modules : </label>

                    <div class="col-sm-4">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">Module Name</th>
                                </tr>
                            </thead>
                            <tbody class="table-body">
                                <tr>
                                    <td>
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="module1" name="module[1]">
                                            <div class="input-group-append">
                                                <button class="input-group-text btn" id="btn-add"><i class="fas fa-plus"></i></button>
                                            </div>
                                        </div>
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
                                                    <button class="input-group-text btn btn-remove"><i class="fas fa-minus"></i></button>
                                            </div>
                                        </div>
                                    </td>
                                </tr>`
                
                return moduleDiv
            }
            
            var i = 2;
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