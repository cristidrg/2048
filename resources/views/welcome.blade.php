<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{csrf_token()}}">
        <title>2048 V7</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
        @if (env('APP_ENV') == 'local')
            <link href="{{ asset('css/app.css') }}" rel="stylesheet">
        @else
            <link href="{{ secure_asset('css/app.css') }}" rel="stylesheet">
        @endif
    </head>
    <body class="overflow-hidden desktop:overflow-auto">
        <div id="app"></div>
        <script src="/js/app.js"></script>
    </body>
</html>
