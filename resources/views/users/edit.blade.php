@extends('layouts.master')

@section('title','Dashboard')

@section('content')

<div class="container">
    <div class="row">
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
                        <label for="country" class="col-sm-2 col-form-label">Country :</label>
                        <div class="col-sm-10">
                            <select class="form-control" id="country" name="country" data-selected="{{ $user->country }}" required>
                                @component('layouts.countries')
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
                    <div class="col-sm-10">
                      <button type="submit" class="btn btn-primary">Update Profile</button>
                    </div>
                  </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('script')
    <script>
        var country = $('#country').attr("data-selected");
         $('#country').val(country);
    </script>
@stop