@extends('layouts.master')

@section('title','Reports')

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-sm-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-4">Schedule Summary</h4>
                        <div class="report-body">
                            <p class="text-dark">Number of Modules in this Schedule : 
                                <span class="text-primary">
                                    @if (! empty($modules))
                                        {{ $modules->count() }}
                                    @else
                                        0
                                    @endif
                                </span>
                            </p>

                            <p class="text-dark">Number of Sessions Completed : 
                                <span class="text-primary">    
                                    @if (! empty($sessions))
                                        {{ $sessions->where('status', 'completed')->count() }}
                                    @else
                                        0
                                    @endif
                                </span>
                            </p>
                            
                            <p class="text-dark">Number of Sessions Missed : 
                                <span class="text-primary">
                                    @if (! empty($modules))
                                        {{ $sessions->where('status', 'incomplete')->count() }}
                                    @else
                                        0
                                    @endif
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')

@endsection