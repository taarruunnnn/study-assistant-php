@extends('layouts.master')

@section('title','Create Schedule')

@section('content')
    <div class="container">
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
            @endif  
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
                events: [
                @if(isset($data)){
                    @foreach ($data as $d)
                        {
                            title: '{{$d['title']}}',
                            start: '{{$d['start']}}',
                            end: '{{$d['end']}}',
                            color: '{{$d['color']}}'
                        },
                    @endforeach
                }
                @endif
                ]
            });
        });
  
    </script>
@endsection
