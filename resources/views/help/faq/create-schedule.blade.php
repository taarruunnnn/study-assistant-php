@extends('layouts.master')

@section('title','Help')

@section('content')

    <div class="container">
        <div class="row">
            <div class="col mb-3">
                <h4>Create Schedule</h4>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-9">
                <p>
                    Creating schedules is one of the primary purposes of this system. This process is very simple and can be
                    completed with just a few clicks.
                </p>
                <div class="row">
                    <div class="col">
                        <h5 class="my-3">Step 1</h5>
                        <img src="{{ asset('storage/images/screenshots/faq/create-schedule/visit-page.gif') }}" alt="Visit page" class="img-fluid" width="800">
                        <p class="my-3">The first step is to login to the system, click on the 'Schedule' icon and click the 'Create Schedule' button</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <h5 class="my-3">Step 2</h5>
                        <img src="{{ asset('storage/images/screenshots/faq/create-schedule/add-modules.gif') }}" alt="Add modules" class="img-fluid" width="800">
                        <p class="my-3">
                            Here, you can add the modules of your current semester. In order to get an idea about a selected
                            module, you can click on the 'Analyze button' to view a brief report of the module if it exists in our database.
                            Once you select a module, please input a rating for the module based on how difficult you think it would be. This rating would 
                            be used to create your personalized study schedule. Click on the 'Add' button afterwards
                        </p>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <h5 class="my-3">Step 3</h5>
                        <img src="{{ asset('storage/images/screenshots/faq/create-schedule/set-duration.gif') }}" alt="Set duration" class="img-fluid" width="800">
                        <p class="my-3">
                            Once you have added all your modules, you can add the duration of your semster. It is recommended to select a data closest
                            to the day your examination begins. Afterwards, select the number of hours you could spend self studying per weekday and per weekend day,
                             and finally, clicking of 'Create Schedule' will create a schedule for you.
                        </p>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <h5 class="my-3">Step 4</h5>
                        <img src="{{ asset('storage/images/screenshots/faq/create-schedule/created-schedule.png') }}" alt="Created Schedule" class="img-fluid" width="800">
                        <p class="my-3">
                            The created schedule will be available in the 'Schedule' section of the system.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection