<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{csrf_token()}}">
        <title>2048 Live</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
        @if (env('APP_ENV') == 'local')
            <link href="{{ asset('css/app.css') }}" rel="stylesheet">
            <link rel="icon" href="{{ asset('images/2048.png') }}" sizes="16x16" type="image/png">
        @else
            <link href="{{ secure_asset('css/app.css') }}" rel="stylesheet">
            <link rel="icon" href="{{ secure_asset('images/2048.png') }}" sizes="16x16" type="image/png">
        @endif
    </head>
    <body class="overflow-hidden desktop:overflow-auto">
        <div id="app"></div>
        <script src="/js/app.js"></script>
    </body>
</html>
