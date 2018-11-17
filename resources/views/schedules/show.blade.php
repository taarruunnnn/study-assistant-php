@extends('layouts.master')

@section('title','Create Schedule')

@section('content')
    <div class="container">
        <div class="row">
           <div class="col-sm mb-3">
                <form action="{{ route('schedules.create') }}">
                    <input type="submit" class="btn btn-primary" value="Create Schedule" >
                </form>
           </div>
        </div>
        <div class="row">
            <div class="col-sm">
                <ul>
                    @foreach ($results as $result)
                        <li>{{ $result }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
@endsection

