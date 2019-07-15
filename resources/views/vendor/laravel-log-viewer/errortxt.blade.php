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
<body style="background-color: #fefefe;height:100vh">
<nav class="navbar navbar-default fixed-top text-center h6" style="background-color:#2d3e50" role="navigation">
    <div class='text-center' style="font-size:0.85rem;color:#FFFFFF;margin:0 auto;">
        Log and error handling system based on rap2hpoutre/laravel-log-viewer and improved by Daniel Nieto
        (https://github.com/nietodaniel)
    </div>
</nav>
<div class="container-fluid align-middle" style="height:90vh">
    <div class="row align-middle" style="height:30%;"></div>
    <div class="row align-middle" style="color:#2d3e50;height:40%">
        <div class="col-md-6 offset-3 text-center align-middle">
            <div class="container-fluid">
                <div class="row" style="padding-top:20px;padding-bottom:20px;">
                    <div class="col-md-3 text-right align-middle">
                        <p class="h2">{{$data['code']}}</p>
                    </div>
                    <div class="col-md-1 text-center align-middle">
                        <p class="h1"> | </p>
                    </div>
                    <div class="col-md-8 text-left align-middle">
                        <p class="h2">{{$data['title']}}</p>
                    </div>
                </div>
                <div class="row" style="padding-top:20px;padding-bottom:20px;">
                    <div class="col-md-10 text-center offset-1">
                        <p class="h3">{{$data['message']}}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<footer id="footer">
    <div class="d-none">loaded</div>
</footer>
</body>
</html>
