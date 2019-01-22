@extends('layouts.master')

@section('title','Dashboard')

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col">
            <h5>Welcome Admin, {{ Auth::user()->name }}</h5>
        </div>
    </div>
    <div class="row">
        <div class="col">
            
        </div>
    </div>
</div>
    
@stop

@section('script')

@endsection
