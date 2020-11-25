<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Weather Dashboard</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="{{ secure_asset('css/app.css') }}" rel="stylesheet" type="text/css">

<body>
    
    <div id="app">
        <weather-dashboard-card></weather-dashboard-card>
    </div>

    <script type="text/javascript" src="{{ secure_asset('js/app.js') }}"></script>

</body>

</html>