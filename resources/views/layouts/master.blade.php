<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Study Assistant') }} - @yield('title')</title>
    <link rel="shortcut icon" href="{{ asset('storage/favicon.ico') }}" type="image/x-icon">
 
    @if(Request::is('user/*'))
        <link href="{{ asset('css/user.css') }}" rel="stylesheet">
    @elseif(Request::is('schedules*') || Request::is('session'))
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

            @if ($flash = session('error'))
                <div class="alert alert-danger alert-dismissible mx-2" role="alert">
                    {{ $flash }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            
            @if ($errors->any())
                <div class="alert alert-danger  mx-2">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif



            {{-- Page Content --}}
                
            @yield('content')
           
        </div>

        
    </div>
    <!-- Scripts -->
    @if (Request::is('user/*'))
        <script src="{{ asset('js/user.js') }}"></script>
    @elseif (Request::is('schedules*') || Request::is('reports*') || Request::is('session') )
        <script src="{{ asset('js/schedule.js') }}"></script>
    @else
        <script src="{{ asset('js/app.js') }}"></script>
    @endif

    @yield('script')
    <script>
            toastr.options = {
                "debug": false,
                "positionClass": "toast-top-right",
                "showDuration": "300",
                "hideDuration": "1000",
                "timeOut": "5000",
            }
            @if ($flash = session('message'))
                toastr.success("{{ $flash }}");
            @endif
    </script>
</body>
</html>