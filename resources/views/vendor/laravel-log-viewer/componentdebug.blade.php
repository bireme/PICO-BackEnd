@extends('vendor.laravel-log-viewer.layout.basicmessage-layout')
@section('pagecontent')
    <div class="row" style="padding-top:20px">
        <p class="h5">Controller: {{$title}}</p>
    </div>
    <hr style="height:3px;" class="btn-dark btn-outline-dark">
    <div class="row text-left">
        <p class="h6">Input Data:</p><br>
    </div>
    <div class="row text-left">
        <?php
        dump($input)
        ?>
    </div>
    <hr style="height:3px;" class="btn-dark btn-outline-dark">
    <div class="row text-left">
        <p class="h6">Output:</p><br>
    </div>
@endsection
