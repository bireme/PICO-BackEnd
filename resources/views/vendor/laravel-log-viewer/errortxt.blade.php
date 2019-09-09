@extends('vendor.laravel-log-viewer.layout.message-layout')
@section('pagecontent')
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
@endsection
