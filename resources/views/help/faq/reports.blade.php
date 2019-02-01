@extends('layouts.master')

@section('title','Help')

@section('content')

    <div class="container">
        <div class="row">
            <div class="col mb-3">
                <h4>Reports</h4>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-9">
                <p>
                    Reporting is an important functionality provided by the system. You can view the progress of your study schedule 
                    anytime you wish to do so. This will give you a comprehensive report of how well you have studied.
                </p>
                <div class="row">
                    <div class="col">
                        <img src="{{ asset('storage/images/screenshots/faq/reports.gif') }}" alt="Reports" class="img-fluid" width="800">
                        <p class="my-3">
                            The report will also consist of a set of predictions about how well you will pass each of your modules.
                            These predictions are based on how much you have studied and how hard each module is for your and
                            for other students.<br/>
                            You can also save these reports for future reference.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection