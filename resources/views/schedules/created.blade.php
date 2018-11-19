@extends('layouts.master')

@section('title','Create Schedule')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-sm-10">
                <ul class="list-group">
                    @foreach ($results as $result)
                        <li class="list-group-item">
                            {{ $result }}
                        </li>
                    @endforeach   
                </ul>
            </div>    
        </div>
    </div>
@endsection