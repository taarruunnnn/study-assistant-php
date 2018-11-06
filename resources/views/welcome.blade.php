<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Lato:300|Open+Sans" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container">
        <div class="row hero-header justify-content-center align-items-center">
            <div class="jumbotron text-center p-5 bg-light">
                <h1 class="display-2 m-4">STUDY ASSISTANT</h1>
                <p>A <em>Smart, Dynamic </em>&<em> Flexible</em> Self Study Time Management Application for Students.</p>
                
                <p class="lead">
                  <a href="{{ route('login') }}" class="btn btn-dark btn-lg m-2" href="#" role="button">Sign In</a>
                  <a href="{{ route('register') }}" class="btn btn-dark btn-lg m-2" href="#" role="button">Register</a>
                </p>
              </div>
        </div>
      </div>
</body>
</html>
