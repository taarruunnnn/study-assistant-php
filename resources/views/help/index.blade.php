@extends('layouts.master')

@section('title','Help')

@section('content')

    <div class="container animated fadeIn">
        <div class="row">
            <div class="col text-center">
                <div class="h4">FAQ</div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6 mx-auto mt-4">
                <div class="list-group">
                    <a href="{{ route('help.faq', ['faq' =>'create-schedule']) }}" class="list-group-item list-group-item-action">How to create a schedule?</a>
                    <a href="{{ route('help.faq', ['faq' =>'modify-schedule']) }}" class="list-group-item list-group-item-action">How to modify a schedule?</a>
                    <a href="{{ route('help.faq', ['faq' =>'reports']) }}" class="list-group-item list-group-item-action">How to obtain reports?</a>
                    <a href="{{ route('help.faq', ['faq' =>'edit-user']) }}" class="list-group-item list-group-item-action">How to change my user information?</a>
                    <a href="#" class="list-group-item list-group-item-action" id="data-collected">What data is collected from me?</a>
                    <div class="card faq-inline" id="data-collected-text">
                        <div class="card-body">
                            <p class="mx-2 mt-2">
                                In order to provide students with analysis about modules, and to make predictions, we collect and analyze some
                                data that you provide the system. These include things like you basic information, your study patterns and your grades.
                                However, none of this data is presented to other users directly. Instead, they are aggregated and presented in the form of
                                average values. Under no circumstance can another user view your personal information. However, administrators are allowed to 
                                view your basic details but administrative priviledges are only available for system management purposes. The privacy of your data is of
                                utmost importance to us so if you have any issue, please read our <a href="{{ route('help.policy') }}" class="btn btn-link p-0">Privacy Policy</a>.
                            </p>
                        </div>
                    </div>
                    <a href="#" class="list-group-item list-group-item-action" id="prediction">How are my grades predicted?</a>
                    <div class="card faq-inline" id="prediction-text">
                        <div class="card-body">
                            <p class="mx-2 mt-2">
                                In order to predict your grades, data is collected from all of the users of this system and a prediction model 
                                is built based on this data. Afterwards, your data is collected and is sent through this model which would then
                                predict the grade which you will receive for your examinations.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')

    <script>
        $(document).ready(function(){
            $('#data-collected').click(function(e){
                e.preventDefault();
                $('#data-collected-text').toggle('slow');
                $('#prediction-text').hide('slow');
            });
            $('#prediction').click(function(e){
                e.preventDefault();
                $('#prediction-text').toggle('slow');
                $('#data-collected-text').hide('slow');
            });
        });
    </script>

@endsection