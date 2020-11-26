<!doctype html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Weather Dashboard</title>
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <link href="<?php echo e(secure_asset('css/app.css')); ?>" rel="stylesheet" type="text/css">

<body>
    
    <div id="app">
        <weather-dashboard-card></weather-dashboard-card>
    </div>

    <script type="text/javascript" src="<?php echo e(secure_asset('js/app.js')); ?>"></script>

</body>

</html><?php /**PATH /projects/challenge/resources/views/weather_dashboard.blade.php ENDPATH**/ ?>