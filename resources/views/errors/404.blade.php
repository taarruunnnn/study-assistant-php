
@extends('layouts.app')

@section('title', '404')

@section('content')
<div class="col-sm-9 col-md-7 col-lg-5 mx-auto">
    <div class="card p-5 my-5 animated fadeIn">
        <div class="error-page">
            <h2>404</h2>
            <p>The page you requested cannot be found.</p>
            <a href="/" class="btn btn-link">Go Home</a>
        </div>
    </div>
</div>

@stop

