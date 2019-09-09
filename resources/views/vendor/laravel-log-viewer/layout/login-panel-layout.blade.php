@extends('vendor.laravel-log-viewer.layout.admin-layout')
@section('pagecontent')
    <div class="row">
        <h1><i class="fa fa-calendar" aria-hidden="true"></i> Admin Login Panel</h1>
        <p class="text-muted"><i>by Rap2h</i></p>
    </div>
    <div class="col-10 table-container">
        @yield('logincontent')
    </div>
@endsection
