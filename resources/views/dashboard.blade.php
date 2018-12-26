@extends('layouts.master')

@section('title','Dashboard')

@section('content')

<div class="container-fluid">
    <div class="row ml-2">
        <div class="card-deck">
            <div class="card text-white bg-dark">
                <div class="card-body">
                    <h5 class="card-title">Today's Sessions</h5>
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
