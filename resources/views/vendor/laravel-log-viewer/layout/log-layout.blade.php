<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="autor" content="{{ __('lang.autor') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex, nofollow">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PICO Explorer log viewer</title>
    <link rel="stylesheet" href="{{ mix('css/log.css') }}">
</head>
<body>
<nav class="navbar navbar-default fixed-top text-center h6" style="background-color:#2d3e50" role="navigation">
    <div class='text-center' style="font-size:0.85rem;color:#FFFFFF;margin:0 auto;">
        Log and error handling system based on rap2hpoutre/laravel-log-viewer and improved by Daniel Nieto (https://github.com/nietodaniel)
    </div>
</nav>
<div class="container-fluid mainwrapper pagecontent">
    <div class="row" style ="height:auto;min-height:100%;">
        <div class="col sidebar mb-3">
            <h1><i class="fa fa-calendar" aria-hidden="true"></i> Laravel Log Viewer</h1>
            <p class="text-muted"><i>by Rap2h</i></p>
            <div class="list-group div-scroll">
                @yield('contentone')
            </div>
        </div>
        <div class="col-10 table-container">
            @yield('contenttwo')
            <div class="p-3">
                @yield('contentthree')
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="{{ mix('js/log.js') }}"></script>
<footer id="footer">
    <div class="d-none">loaded</div>
</footer>
</body>
</html>
