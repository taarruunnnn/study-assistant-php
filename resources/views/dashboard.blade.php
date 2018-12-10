@extends('layouts.master')

@section('title','Dashboard')

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-md-3">
            <div class="card text-white bg-dark">
                <div class="card-body">
                    <h5 class="card-title">Today's Schedule</h5>
                    @if(isset($modules))
                        <ul>
                            @foreach ($modules as $module)
                                <li class="card-text">{{ $module }} - 2 Hours</li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
</div>

@stop
