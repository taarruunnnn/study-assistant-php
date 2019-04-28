@extends('layouts.app')

@section('title', 'Register')

@section('content')
<div class="col-sm-9 mx-auto my-5">
    <div class="card">
        <div class="card-body">
            <a href="{{ route("login") }}" id="form-back">&larr;</a>
            <h2 class="card-title text-center mb-5 mt-0" >Register</h2>
            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="form-group row">
                    <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>

                    <div class="col-md-6">
                        <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name') }}" required autofocus>

                        @if ($errors->has('name'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('name') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="form-group row">
                    <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                    <div class="col-md-6">
                        <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required>

                        @if ($errors->has('email'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="form-group row">
                    <label for="birth" class="col-md-4 col-form-label text-md-right">Birth Year</label>
                
                    <div class="col-md-2">
                        <input  type="text" id="birth" class="datepicker-years text-center form-control {{ $errors->has('birth') ? ' is-invalid' : '' }}" name="birth" required>

                        @if ($errors->has('birth'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('birth') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="form-group row">
                    <label for="gender" class="col-md-4 col-form-label text-md-right" id="genderLabel">Gender</label>
                
                    <div class="btn-group btn-group-toggle col-md-4" data-toggle="buttons">
                        <label class="btn btn-secondary" id="maleLbl">
                            <input type="radio" name="gender" id="male" autocomplete="off" value="M" required> Male
                        </label>
                        <label class="btn btn-secondary" id="femaleLbl">
                            <input type="radio" name="gender" id="female" autocomplete="off" value="F"> Female
                        </label>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="country" class="col-md-4 col-form-label text-md-right">Country</label>

                    <div class="col-md-4">
                        <select class="form-control" id="country" name="country" required>
                            @component('layouts.countries')
                            @endcomponent
                        </select>

                        @if ($errors->has('country'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('country') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="form-group row">
                    <label for="university" class="col-md-4 col-form-label text-md-right">University</label>

                    <div class="col-md-6">
                        <div id="typeahead-university">
                            <input id="university" class="typeahead form-control {{ $errors->has('university') ? ' is-invalid' : '' }}" type="text" placeholder="Enter you University" name="university" required>

                            @if ($errors->has('university'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('university') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="major" class="col-md-4 col-form-label text-md-right">Major</label>

                    <div class="col-md-6">
                        <select class="form-control" id="major" name="major" required>
                            <option value="" disabled selected id="placeholder">What is your field of study?</option>
                            @component('layouts.majors')
                            @endcomponent
                        </select>

                        @if ($errors->has('major'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('major') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>


                <div class="form-group row">
                    <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                    <div class="col-md-6">
                        <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

                        @if ($errors->has('password'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="form-group row">
                    <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>

                    <div class="col-md-6">
                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                    </div>
                </div>

                <div class="form-group row d-flex justify-content-center mt-5">
                    <div class="col-md-6">
                        <button type="submit" class="btn btn-primary w-100">
                            {{ __('Register') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
