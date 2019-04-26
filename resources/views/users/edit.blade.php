@extends('layouts.master')

@section('title','Edit User')

@section('content')

<div class="container-fluid">
    <div class="row justify-content-md-center">
        <div class="col-sm-9">
            <form method="POST" action="{{ route('user.update', $user->name) }}">
                @csrf
                @method('PATCH')
            
                <div class="form-group row">
                    <label for="name" class="col-sm-2 col-form-label">Name :</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="name"  name="name" value="{{ $user->name }}" required />
                    </div>
                </div>
        
                <div class="form-group row">
                    <label for="email" class="col-sm-2 col-form-label">Email :</label>
                    <div class="col-sm-10">
                        <input type="email" class="form-control" id="email"  name="email" value="{{ $user->email }}" required/>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="birth" class="col-sm-2 col-form-label">Birth Year: </label>
                
                    <div class="col-md-2">
                        <div class="input-group">
                            <input type="text" class="datepicker-years text-center form-control" id="birth" name="birth" value="{{ $user->birth }}">
                            <div class="input-group-append">
                                <span class="input-group-text" id="calendarBtn"><i class="fas fa-calendar-alt"></i></span>
                            </div>
                        </div>

                        @if ($errors->has('birth'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('birth') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="form-group row" id="gender" data-selected="{{ $user->gender }}">
                        <label for="gender" class="col-sm-2 col-form-label">Gender: </label>
                    
                        <div class="btn-group btn-group-toggle col-md-4" data-toggle="buttons">
                            <label class="btn btn-secondary" id="maleBtn">
                                <input type="radio" name="gender" id="male" autocomplete="off" value="M" > Male
                            </label>
                            <label class="btn btn-secondary" id="femaleBtn">
                                <input type="radio" name="gender" id="female" autocomplete="off" value="F"> Female
                            </label>
    
                            @if ($errors->has('gender'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('gender') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                <div class="form-group row">
                    <label for="country" class="col-sm-2 col-form-label">Country :</label>
                    <div class="col-sm-4">
                        <select class="form-control" id="country" name="country" data-selected="{{ $user->country }}" required>
                            @component('layouts.countries')
                            @endcomponent
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="university" class="col-sm-2 col-form-label">University :</label>
                    <div id="typeahead-university" class="col-sm-10">
                        <input id="university" class="typeahead form-control" type="text" name="university" value="{{ $user->university }}"/>
                    </div>
                </div>


                <div class="form-group row">
                    <label for="major" class="col-sm-2 col-form-label">Major :</label>
                    <div class="col-sm-4">
                        <select class="form-control" id="major" name="major" data-selected="{{ $user->major }}" required>
                            @component('layouts.majors')
                            @endcomponent
                        </select>
                    </div>
                </div>
        
                <div class="form-group row">
                    <label for="password" class="col-sm-2 col-form-label">Password :</label>
                    <div class="col-sm-10">
                        <input type="password" class="form-control" id="password"  name="password" />
                    </div>
                </div>
        
                <div class="form-group row">
                    <label for="password-confirm" class="col-sm-2 col-form-label">Confirm Password :</label>
                    <div class="col-sm-10">
                        <input type="password" class="form-control" id="password-confirm"  name="password_confirmation" />
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-sm-4 ml-5"><a href="#" class="text-primary" data-toggle="modal" data-target="#deleteConfirm" id="deleteBtn">Delete Account</a></div>
                </div>
                
                <div class="form-group row">
                    <div class="col-sm-10">
                      <button type="submit" class="btn btn-primary">Update Profile</button>
                    </div>
                  </div>
            </form>
        </div>
    </div>
    <div class="row my-5">
        <div class="col text-center">
            <a href="{{ route('help.policy') }}" class="btn btn-link">Privacy Policy</a>
        </div>
    </div>
</div>

@endsection


@section('modal')
    <div class="modal fade" id="deleteConfirm" tabindex="-1" role="dialog" aria-labelledby="deleteConfirmLabel" aria-hidden="true">   
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="deleteConfirmLabel">Delete User</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              Are you sure you want to delete your account?
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                <form action="{{ route('user.delete', $user->name) }}" method="POST">
                    @method('DELETE')
                    @csrf
                    <button type="submit" class="btn btn-danger">Yes</button>
                </form>
            </div>
          </div>
        </div>
      </div>
@endsection

@section('script')
    <script>
        $(function() {
            initUser();

            function initUser()
            {
                var country = $('#country').attr("data-selected");
                $("#country").val(country);

                var major = $('#major').attr("data-selected");
                $("#major").val(major);


                var gender = $('#gender').attr("data-selected");
                if (gender == 'M'){
                    $("#maleBtn").button('toggle')
                } else if (gender == 'F'){
                    $("#femaleBtn").button('toggle')
                }
                
                $("#name").focus();

                $("#calendarBtn").click(function() {
                    $("#birth").datepicker('show');
                });
            }
        });
    </script>
@endsection
