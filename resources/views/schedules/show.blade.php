@extends('layouts.master')

@section('title','Schedule')

@section('content')
    <div class="container">
        <div class="row mt-3">
            <div class="schedule-details animated fadeIn delay-1s mx-3">
                @if($toarchive === true)
                    <div class="overlay animated fadeIn delay-1s" id="archive-overlay">
                        <div class="archive-message text-center d-flex flex-column align-items-center justify-content-center h-100">
                            <a href="#" id="overlay-close">&times;</a>
                            <i class="fas fa-laugh-beam overlay-icon"></i>
                            <h4>Congratulations. You have completed this schedule.</h4>
                            <p>Please archive it to create a new schedule.</p>
                            <button type="button" class="btn btn-danger" id="archiveBtn"><i class="fas fa-archive"></i>&nbsp;&nbsp;Archive Sessions</button>
                            <div id="confirmArchive">Are you sure you want to <strong>Archive Current Schedule?</strong>
                                <br/>
                                <a class="btn btn-outline-danger btn-sm" href="{{ route('schedules.archive') }}">Yes</a>
                                <a class="btn btn-outline-secondary btn-sm" id="cancelArchive">No</a>
                            </div>
                        </div>
                    </div>
                @endif
                @if (Auth::user()->schedule === null)
                    <div class="overlay dflex justify-content-center d-flex align-items-center animated fadeIn delay-1s">
                        <div class="create-schedule-message text-center d-flex flex-column align-items-center">
                            <i class="far fa-calendar-plus overlay-icon"></i>
                            <h4>Create a Schedule.</h4>
                            <p>There are no active schedules. Please create one to continue.</p>
                            <form action="{{ route('schedules.create') }}">
                                <input type="submit" class="btn btn-primary" value="Create Schedule" >
                            </form>
                        </div>
                    </div>
                @endif
                <div id="calendar"></div>
            </div>
        </div>
    </div>    
@endsection

@section('modal')
    @if(isset($schedule))
        {{-- Modify Schedule Modal --}}
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
                                <div class="row">
                                    <p>
                                        Please note that modifying an existing schedule will reset the progress of your schedule and create a new schedule for you.
                                        If this is not what you want, please consider moving sessions.
                                    </p>
                                    <hr class="w-100 mt-0">
                                </div>
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
                                        <label class="col-sm-6 col-form-label">How many hours per day can you study?</label>
                                    
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
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        <div class="row">
                                            <button class="btn ml-4" id="btn-add"><i class="fas fa-plus"></i></button>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-4">
                                    <div class="col-sm-10">
                                        <button type="button" class="btn btn-danger" id="scheduleDelete">Delete Schedule</button>
                                        <p id="confirmDelete">Are you sure you want to <strong>Delete Current Schedule?</strong>
                                            <br/>
                                            <a class="btn btn-outline-primary btn-sm" id="btnYes" href="{{ route('schedules.destroy') }}">Yes</a>
                                            <a class="btn btn-outline-secondary btn-sm" id="cancelScheduleDelete">No</a>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <input type="submit" class="btn btn-primary" value="Save Changes" id="editScheduleSubmit">
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Add Event Modal --}}
        <div class="modal fade" id="addEvent" tabindex="-1" role="dialog" aria-labelledby="addEventLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addEventLabel">Event</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="container">
                            <div class="row">
                                <div class="col">
                                    <form action="{{ route('events.store') }}" id="eventForm" method="POST">
                                        @csrf
                                        <input type="hidden" name="eventname" id="eventid">
                                        <div class="form-group row">
                                            <label for="eventdate" class="col-sm-3 col-form-label">Date</label>
                                            <input type="text" class="datepicker text-center form-control col-sm-6" id="eventdate" name="eventdate">
                                        </div>
                                        <div class="row mt-4">
                                            <label for="description" class="col-sm-3 col-form-label">Description</label>
                                            <textarea rows="2" class="form-control col-sm-9" id="description" name="description"></textarea>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col">
                                    <button type="button" class="btn btn-danger" id="eventDelete">Delete Event</button>
                                    <p id="confirmEventDelete">Are you sure you want to <strong>Delete This Event?</strong>
                                        <br/>
                                        <a class="btn btn-outline-primary btn-sm" id="eventDeleteYes">Yes</a>
                                        <a class="btn btn-outline-secondary btn-sm" id="canceleventDelete">No</a>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="createEvent">Save changes</button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Session Details Modal --}}
        <div class="modal fade" id="sessionDetails" tabindex="-1" role="dialog" aria-labelledby="sessionDetailsLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="sessionDetailsLabel">Session Details</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="container">
                            <div class="row">
                                <div class="col-sm-9">
                                    <h4 id="session-name"></h4>
                                    <p>2 Hour Session<br/>
                                        <span id="session-date"></span><br/>
                                        <span class="badge badge-secondary session-badge" id="session-status"></span>
                                    </p>
                                </div>
                                <div class="col-sm-3">
                                    <div id="btn-study">
                                        <a href="{{ route('session.show') }}" class="btn btn-success"><i class="fas fa-clock"></i>Study</a>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <button class="btn btn-link pl-0" id="session-change-btn">Change Session Date</button>
                                    <form action="{{ route('schedules.move') }}" method="POST" class="form-inline mt-2" id="session-change-form" style="display:none;">
                                        @csrf
                                        <label for="session-date-new">Date</label>
                                        <input type="text" class="form-control mx-3 w-50 text-center" id="session-date-new" name="date">
                                        <input type="hidden" id="session-id" name="id">
                                        <button type="submit" class="btn btn-primary">Change</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
    @routes
@endsection
@section('script')
    <script src="{{ asset('js/calendar.js') }}"></script>
    <script>
        $(document).ready(function(){

            var today = moment().startOf('day').toISOString();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });  

            initModifySchedule();
            initDatePicker();

            $('#overlay-close').click(function(e) {
                e.preventDefault();
                $('#archive-overlay').removeClass('fadeIn delay-1s');
                $('#archive-overlay').addClass('fadeOut');
                $('#archive-overlay').one('animationend', function() {
                    $('#archive-overlay').remove();
                })
            })

            $('#modifySchedule').on('hidden.bs.modal', function (e) {
                $("#confirmDelete").hide();
            });

            $( "#scheduleDelete" ).click(function(e) {
                e.preventDefault();
                $("#confirmDelete").toggle("slow");
            });

            $('#cancelScheduleDelete').click(function(e) {
                e.preventDefault();
                $("#confirmDelete").hide("slow");
            });

            $('#archiveBtn').click(function(){
                $('#confirmArchive').toggle('slow')
            });

            $('#cancelArchive').click(function(e){
                e.preventDefault();
                $('#confirmArchive').hide('slow')
            })

            $('#createEvent').click(function(){
                $('form#eventForm').submit();
            })

            // Remove modal data on exit
            $('#addEvent').on('hidden.bs.modal', function(){
                $('#eventdate').val("").datepicker("update");
                $('#description').val("");
                $("#eventId").remove();
                $('#eventDelete').css('display','none');
            });

            $( "#eventDelete" ).click(function(e) {
                e.preventDefault();
                $("#confirmEventDelete").toggle("slow");
            });

            $('#cancelEventDelete').click(function(e) {
                e.preventDefault();
                $("#confirmEventDelete").hide("slow");
            });

            $('#eventDeleteYes').click(function(e){
                e.preventDefault();
                var eventId = $('#eventId').val();
                $('<form action="{{ route('events.destroy') }}" method="POST">@csrf<input type="hidden" name="id" value="'+eventId+'"></form>').appendTo('body').submit();
            })

            $('#addEventButton').click(function(e){
                $('#eventForm').attr("action", "{{ route('events.store') }}");
            })

            $('#editScheduleSubmit').click(function(e){
                $('.module-names').each(function(index, data){
                    moduleName = $(this).val();

                    if (moduleName.trim() === "" || moduleName === null){
                        e.preventDefault();
                        return false;
                    }
                })
            })

            function initDatePicker ()
            {
                $('#start').datepicker({
                    maxViewMode: 'years',
                    format: "yyyy-mm-dd",
                    autoclose: true,
                    todayHighlight: true,
                    weekStart: 1
                }).on('changeDate', function(selected){
                    var minDate = new Date(selected.date.valueOf());
                    $('#end').datepicker('setStartDate', minDate);

                    var maxDate = new Date(selected.date.valueOf());
                    maxDate = new Date(maxDate.setMonth(maxDate.getMonth()+11));
                    $('#end').datepicker('setEndDate', maxDate);
                });

                $('#end').datepicker({
                    maxViewMode: 'years',
                    format: "yyyy-mm-dd",
                    todayHighlight: true,
                    autoclose: true,
                    weekStart: 1
                }).on('changeDate', function (selected) {
                    var maxDate = new Date(selected.date.valueOf());
                    $('#start').datepicker('setEndDate', maxDate);
                });

                $('#eventdate').datepicker({
                    maxViewMode: 'years',
                    autoclose: true,
                    format: "yyyy-mm-dd",
                    todayHighlight: true,
                    weekStart: 1
                });

                @if(isset($schedule))
                    $('#start').datepicker('update', '{{ $schedule->start }}');
                    $('#end').datepicker('update', '{{ $schedule->end }}');
                    // $('#start').datepicker('setStartDate', '{{ $schedule->start }}');
                    $('#end').datepicker('setStartDate', '{{ $schedule->start }}');

                    var maxDate = new Date('{{ $schedule->start }}');
                    maxDate = new Date(maxDate.setMonth(maxDate.getMonth()+11));
                    $('#end').datepicker('setEndDate', maxDate);

                    $('#eventdate').datepicker('setStartDate', '{{ $schedule->start }}');
                    $('#eventdate').datepicker('setEndDate', '{{ $schedule->end }}');

                    window.scheduleStartDate = "{{ $schedule->start }}";
                    window.scheduleEndDate = "{{ $schedule->end }}";
                @endif
            }

            
            @if(isset($schedule))

                $('#session-date').datepicker({
                    maxViewMode: 'years',
                    format: "yyyy-mm-dd",
                    todayHighlight: true,
                    autoclose: true,
                });

                function initSessionDateChanger(calEvent)
                {
                    $('#session-name').text(calEvent.title);
                    $('#session-id').val(calEvent.id);
                    $('#session-date').datepicker('update', calEvent.start.toISOString())
                    $('#session-date-change').show('slow');

                }
            @endif

            function addModuleInput(i)
            {
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

            function initModifySchedule()
            {
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
            }

            
            
        });
  
    </script>
@endsection
