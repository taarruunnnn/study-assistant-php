@extends('layouts.master')

@section('title','Move Schedule Sessions')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-10">
                    <div id='calendar'></div>
            </div>  
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function(){
            
            $('#calendar').fullCalendar({
                themeSystem: 'bootstrap4',
                height: 500,
                firstDay: 1,
                eventColor: '#2196f3',
                eventTextColor: '#FFF',
                eventStartEditable: true,
                eventDrop: function(event, delta, revertFunc){
                    console.log(event.title + " was dropped on "+ event.start.format());
                    if (!confirm("Are you sure about this change?")) {
                        revertFunc();
                    }
                    else {
                        dropSession(event);
                    }
                },
                events: [
                @if(isset($data))
                    @foreach ($data as $d)
                        {
                            id: '{{$d['id']}}',
                            title: '{{$d['title']}}',
                            start: '{{$d['start']}}',
                            end: '{{$d['end']}}',
                            color: '{{$d['color']}}'
                        },
                    @endforeach
                @endif
                ]
            });


            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            function dropSession(event)
            {

                $.ajax({
                    type: 'POST',
                    url: '{{ route('schedule.drop') }}',
                    data: {id: event.id, date: event.start.format()},
                    success: function(message){
                        console.log(message);
                    },
                    error: function(message){
                        console.log(message);
                    }
                });
            }
            
        });
  
    </script>
@endsection
