@extends('vendor.laravel-log-viewer.layout.admin-layout')
@section('pagecontent')
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
@endsection
