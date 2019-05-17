@extends('layouts.master')

@section('title','Help')

@section('content')

    <div class="container">
        <div class="row">
            <div class="col mb-3">
                <h4>Edit User</h4>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-9">
                <p>
                    Users can change their personal information at any time by simply visiting the 'User' section from the sidebar.
                </p>
                <div class="row">
                    <div class="col">
                        <img src="{{ asset('storage/images/screenshots/faq/edit-user.gif') }}" alt="Move Sessions" class="img-fluid" width="800">
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection