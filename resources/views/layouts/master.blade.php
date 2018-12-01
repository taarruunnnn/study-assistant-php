<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - @yield('title')</title>
 
    @if(Request::is('user/*'))
        <link href="{{ asset('css/user.css') }}" rel="stylesheet">
    @elseif(Request::is('schedules*'))
        <link href="{{ asset('css/schedule.css') }}" rel="stylesheet">
    @else
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @endif
</head>
<body>
    
    @yield('modal')

    <div class="wrapper">
        @include('layouts.sidebar')

        {{-- Page Content Holder --}}
        <div id="content">
            @include('layouts.header')

            {{-- Alerts --}}
            @if ($flash = session('message'))
                <div class="alert alert-success alert-dismissible fade show" role="alert" id="messageAlert">
                    {{ $flash }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif



            {{-- Page Content --}}
                
            @yield('content')
           
            @include('layouts.footer')
        </div>

        
    </div>
</body>
 <!-- Scripts -->
@if (Request::is('user/*'))
    <script src="{{ asset('js/user.js') }}"></script>
@elseif (Request::is('schedules*'))
    <script src="{{ asset('js/schedule.js') }}"></script>
@else
    <script src="{{ asset('js/app.js') }}"></script>
@endif
    
 @yield('script')
</html>