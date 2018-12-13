@extends('layouts.master')

@section('title','Reports')

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-sm-2">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-4">Summary</h4>
                        <div class="report-body">
                            <div class="border p-2">
                                <h6>Number of Modules</h6>
                                @if (! empty($modules))
                                    <p class="display-4">{{ $modules->count() }}</p>
                                @else
                                    <p class="display-4">0</p>
                                @endisset
                            </div>
                            
                            <div class="border p-2">
                                <h6>Completed Sessions</h6>
                                    @if (! empty($sessions))
                                        <p class="display-4">{{ $sessions->where('status', true)->count() }}</p>
                                    @else
                                        <p class="display-4">0</p>
                                    @endisset
                            </div>

                            <div class="border p-2">
                                <h6>Missed Sessions</h6>
                                    @if (! empty($modules))
                                        <p class="display-4">{{ $sessions->where('status', false)->count() }}</p>
                                    @else
                                        <p class="display-4">0</p>
                                    @endisset
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')

@endsection