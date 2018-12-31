<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Study Assistant') }}</title>

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
    <div class="container">
        <div class="row my-2">
            <main class="py-4">
                @if ($flash = session('message'))
                    <div class="alert alert-success">
                        {{ $flash }}
                    </div>
                @endif
            </main>
        </div>
        <div class="row">
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
