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
@include('vendor.laravel-log-viewer.partials.navbar')
@spaceless
<div class="container-fluid align-middle">
    <div class="row align-middle" style="margin-top:30px;"></div>
    <div class="row align-middle" style="color:#2d3e50">
        <div class="col-md-12 text-center align-middle">
            <div class="container-fluid">
                @yield('pagecontent')
            </div>
        </div>
    </div>
@endspaceless
