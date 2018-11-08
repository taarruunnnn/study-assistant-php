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
                <div class="alert alert-success">
                    {{ $flash }}
                </div>
            @endif

            
            {{-- CHANGE THIS --}}
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
@if(Request::is('user/*'))
    <script src="{{ asset('js/user.js') }}"></script>
@else
    <script src="{{ asset('js/app.js') }}"></script>
@endif
    
 @yield('script')
</html>