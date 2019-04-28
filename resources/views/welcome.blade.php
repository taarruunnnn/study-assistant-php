
@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="col-sm-9 col-md-7 col-lg-5 mx-auto">
    <div class="card p-5 my-5 animated fadeIn">
        <h2 class="card-title text-center my-3" id="login-header">Study Assistant</h2>
        <form method="POST" action="{{ route('login') }}" class="mt-4">
            @csrf
            <div class="form-group row">
                <input type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" id="email" aria-describedby="email" placeholder="Enter email" name="email" value="{{ old('email') }}" required autofocus>
                
                @if ($errors->has('email'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                @endif
            </div>
            <div class="form-group row">
                <input type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" id="password" placeholder="Password" name="password" required>
            
                @if ($errors->has('password'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
                @endif
            </div>

            <div class="form-group form-check">
                <input type="checkbox" class="form-check-input" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                <label class="form-check-label" for="remember">Keep Me Logged In</label>
            </div>
            <div class="form-group row d-flex justify-content-center">
                <button type="submit" class="btn btn-primary w-100">Login</button>
            </div>
        </form>

        <div class="mt-4">
            <span id="form-register"><a href="{{ route('register') }}">Create an account &rarr;</a></span><br/>
            <span id="form-forgot"><a href="{{ route('password.request') }}">Forgot Password</a></span>
        </div>
    </div>
</div>

@stop

