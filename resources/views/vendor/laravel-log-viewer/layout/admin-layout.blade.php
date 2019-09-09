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
@include('vendor.laravel-log-viewer.partials.navbar')
<div class="container-fluid mainwrapper pagecontent">
    <div class="row" style ="height:auto;min-height:100%;">
       @yield('pagecontent')
    </div>
</div>
<script type="text/javascript" src="{{ mix('js/log.js') }}"></script>
<footer id="footer">
    <div class="d-none">loaded</div>
</footer>
</body>
</html>
