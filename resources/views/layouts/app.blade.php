<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" id="app">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Study Assistant') }} - @yield('title')</title>
    <link rel="shortcut icon" href="{{ asset('storage/favicon.ico') }}" type="image/x-icon">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Merriweather" rel="stylesheet">

   <!-- Styles -->
    @if(Request::is('register'))
        <link href="{{ asset('css/user.css') }}" rel="stylesheet">
    @else
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @endif

</head>
<body id="welcome">
    <div class="container d-flex align-items-center justify-content-center flex-column mb-0">
        @if ($flash = session('message'))
            <div class="row my-2">
                <main class="py-4">
                    <div class="alert alert-success">
                        {{ $flash }}
                    </div>
                </main>
            </div>
        @endif
        <div class="row w-100">
            @yield('content')
        </div>
    </div>
    
    @if(Request::is('register'))
        <script src="{{ asset('js/user.js') }}"></script>
    @else
        <script src="{{ asset('js/app.js') }}"></script>
    @endif

    @yield('script')
</body>


</html>
