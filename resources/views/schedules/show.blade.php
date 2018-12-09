@extends('layouts.master')

@section('title','Create Schedule')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-10">
                    <div id='calendar'></div>
            </div>  
            @if (Auth::user()->schedule === null)
                <div class="col-sm-2">
                    <form action="{{ route('schedules.create') }}">
                        <input type="submit" class="btn btn-primary" value="Create Schedule" >
                    </form>
                </div>
            @else
            <div class="col-sm-2">
                <div class="row">
                    <button class="btn btn-primary" data-toggle="modal" data-target="#modifySchedule"><i class="fas fa-cog"></i>&nbsp;&nbsp;Modify Schedule</button>
                </div>
            </div>
            @endif
        </div>
    </div>

    @if(isset($schedule))
        <!-- Modal -->
        <div class="modal fade" id="modifySchedule" tabindex="-1" role="dialog" aria-labelledby="modifyScheduleLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <form method="POST" action="{{ route('schedules.update') }}" id="editSchedule">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="modifyScheduleLabel">Modify Schedule</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="container-fluid">
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label">Duration : </label>
                                
                                    <div class="col-sm-8">
                                        <div class="input-group input-daterange">
                                            <input type="text" class="datepicker text-center form-control" id="start" name="start">
                                            <div class="input-group-append">
                                                <span class="input-group-text">to</span>
                                            </div>
                                            <input type="text" class="datepicker text-center form-control" id="end" name="end">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row">
                                        <label class="col-sm-6 col-form-label">How many hours per day can you study? : </label>
                                    
                                        <div class="col-sm-6">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <select class="form-control" name="weekdays" id="weekdays">
                                                        <option value="2" @if ($schedule->weekday_hours == 2) selected="selected" @endif>2</option>
                                                        <option value="4" @if ($schedule->weekday_hours == 4) selected="selected" @endif>4</option>
                                                        <option value="6" @if ($schedule->weekday_hours == 6) selected="selected" @endif>6</option>
                                                        <option value="8" @if ($schedule->weekday_hours == 8) selected="selected" @endif>8</option>
                                                        <option value="10" @if ($schedule->weekday_hours == 10) selected="selected" @endif>10</option>
                                                        <option value="12" @if ($schedule->weekday_hours == 12) selected="selected" @endif>12</option>
                                                    </select>
                                                    <label for="weekdays">On Weekdays</label>
                                                </div>
                                                <div class="col-sm-6">
                                                        <select class="form-control" name="weekends" id="weekends">
                                                            <option value="2" @if ($schedule->weekend_hours == 2) selected="selected" @endif>2</option>
                                                            <option value="4" @if ($schedule->weekend_hours == 4) selected="selected" @endif>4</option>
                                                            <option value="6" @if ($schedule->weekend_hours == 6) selected="selected" @endif>6</option>
                                                            <option value="8" @if ($schedule->weekend_hours == 8) selected="selected" @endif>8</option>
                                                            <option value="10" @if ($schedule->weekend_hours == 10) selected="selected" @endif>10</option>
                                                            <option value="12" @if ($schedule->weekend_hours == 12) selected="selected" @endif>12</option>
                                                        </select>
                                                        <label for="weekends">On Weekdends</label>
                                                    </div>
                                            </div>
                    
                                            @if ($errors->has('weekdays'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('weekday') }}</strong>
                                                </span>
                                            @endif
                                            @if ($errors->has('weekends'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('weekends') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                <div class="form-group row">
                                    <div class="col-sm-12">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th scope="col">Module Name</th>
                                                    <th scope="col">Rating</th>
                                                    <th></th>
                                                    <th scope="col">Avg Rating</th>
                                                </tr>
                                            </thead>
                                            <tbody class="table-body">
                                                @foreach ($modules as $key => $module)
                                                    <tr>
                                                        <td>
                                                            <div class="input-group">
                                                            <input type="text" class="form-control module-names" id="module{{ $key }}" name="module[{{ $key }}]" value="{{ $module->name }}">
                                                                <div class="input-group-append">
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="input-group">
                                                                <select class="form-control" name="rating[{{ $key }}]">
                                                                    <option value="1" @if ($module->rating == 1)selected="selected" @endif>1</option>
                                                                    <option value="2" @if ($module->rating == 2)selected="selected" @endif>2</option>
                                                                    <option value="3" @if ($module->rating == 3)selected="selected" @endif>3</option>
                                                                    <option value="4" @if ($module->rating == 4)selected="selected" @endif>4</option>
                                                                    <option value="5" @if ($module->rating == 5)selected="selected" @endif>5</option>
                                                                    <option value="6" @if ($module->rating == 6)selected="selected" @endif>6</option>
                                                                    <option value="7" @if ($module->rating == 7)selected="selected" @endif>7</option>
                                                                    <option value="8" @if ($module->rating == 8)selected="selected" @endif>8</option>
                                                                    <option value="9" @if ($module->rating == 9)selected="selected" @endif>9</option>
                                                                    <option value="10" @if ($module->rating == 10)selected="selected" @endif>10</option>
                                                                </select>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <button class="btn btn-remove"><i class="fas fa-minus"></i></button>
                                                        </td>
                                                        <td><div class="avg mt-2 text-center" id="avg{{ $key }}"></div></td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        <div class="row">
                                            <button class="btn ml-4" id="btn-add"><i class="fas fa-plus"></i></button>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <button class="btn btn-secondary" id="analyze">Analyze Modules</button>
                                    </div>
                                </div>
                                <div class="row mt-4">
                                    <div class="col-sm-10">
                                        <button type="button" class="btn btn-danger" id="scheduleDelete">Delete Schedule</button>
                                        <p id="confirmDelete">Are you sure you want to <strong>Delete Current Schedule?</strong>
                                            <br/>
                                            <a class="btn btn-outline-primary btn-sm" href="{{ route('schedules.destroy') }}">Yes</a>
                                            <a class="btn btn-outline-secondary btn-sm" id="cancelScheduleDelete">No</a>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <input type="submit" class="btn btn-primary" value="Save Changes">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@endsection

@section('script')
    <script>
        $(document).ready(function(){

            $("#analyze").click(function(e){
                e.preventDefault();
                analyzeModules();
            });

            $('#start').datepicker('update', '@if(isset($schedule)){{ $schedule->start }}@endif');
            $('#end').datepicker('update', '@if(isset($schedule)){{ $schedule->end }}@endif');
            
            $('#calendar').fullCalendar({
                themeSystem: 'bootstrap4',
                height: 500,
                firstDay: 1,
                eventColor: '#2196f3',
                eventTextColor: '#FFF',
                events: [
                @if(isset($data))
                    @foreach ($data as $d)
                        {
                            title: '{{$d['title']}}',
                            start: '{{$d['start']}}',
                            end: '{{$d['end']}}',
                            color: '{{$d['color']}}'
                        },
                    @endforeach
                @endif
                ]
            });

            $( "#scheduleDelete" ).click(function(e) {
                e.preventDefault();
                $("#confirmDelete").toggle("slow");
            });

            $('#modifySchedule').on('hidden.bs.modal', function (e) {
                $("#confirmDelete").hide();
            });

            $('#cancelScheduleDelete').click(function(e) {
                e.preventDefault();
                $("#confirmDelete").hide("slow");
            });

            function addModuleInput(i){
                var moduleDiv = `<tr>
                                    <td>
                                        <div class="input-group">
                                            <input type="text" class="form-control module-names" id="module`+ i +`" name="module[`+ i +`]">
                                            <div class="input-group-append">
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-group">
                                            <select class="form-control" name="rating[`+ i +`]">
                                                <option value="1">1</option>
                                                <option value="2">2</option>
                                                <option value="3">3</option>
                                                <option value="4">4</option>
                                                <option value="5">5</option>
                                                <option value="6">6</option>
                                                <option value="7">7</option>
                                                <option value="8">8</option>
                                                <option value="9">9</option>
                                                <option value="10">10</option>
                                            </select>
                                        </div>
                                    </td>
                                    <td>
                                        <button class="btn btn-remove"><i class="fas fa-minus"></i></button>
                                    </td>
                                    <td><div class="avg mt-2 text-center" id="avg`+i+`"></div></td>
                                </tr>`
                
                return moduleDiv
            }
            
            @if(isset($schedule))
                var i = {{ count($schedule->modules) }};
            @endif
            var max_fields = 10;

            //append when add button is clicked
            $('#modifySchedule').on('click','#btn-add', function(e){
                e.preventDefault();

                if(i < max_fields){
                    $(".table-body").append(addModuleInput(i));
                    i++;
                }else{
                    $("#btn-add").prop("disabled", true);
                }
            });

            //remove when remove button is clicked
            $('.table-body').on('click','.btn-remove', function(e){
                e.preventDefault();
                $(this).parents('tr').remove();
                console.log('tr');
                i--;
            });

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            analyzeModules()

            function analyzeModules()
            {
                
                var values = new Array();
                $(".module-names").each(function(){
                    values.push($(this).val());
                });

                console.log(values)

                $.ajax({
                    type: 'POST',
                    url: '{{ route('schedule.analyze') }}',
                    data: {modules: values},
                    success: function(analysis){
                        displayAnalysis(analysis);
                    },
                    error: function(message){
                        console.log(message);
                    }
                });
            }

            function displayAnalysis(analysis)
            {
                console.log(analysis);

                for (var key in analysis)
                {
                    if (analysis.hasOwnProperty(key))
                    {
                        var module = key;
                        var rating = analysis[key];

                        for (var x = 0; x <= i; x++)
                        {
                            var textBoxForModule = $("#module" + x);
                            if ($(textBoxForModule).val() == module)
                            {
                                var avg = '#avg'+x
                                $(avg).text(rating);
                            }
                        }
                    }
                }
            }
            
        });
  
    </script>
@endsection
