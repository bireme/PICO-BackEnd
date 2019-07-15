<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        @include('partials.head')
    </head>
    <body>
        @include('partials.header')
        @yield('content')
        @include('partials.footer')
        @include('partials.modais')
        @include('partials.lang')
        @include('partials.footer-scripts')
    </body>
</html>
