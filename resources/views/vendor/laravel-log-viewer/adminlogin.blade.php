@extends('vendor.laravel-log-viewer.layout.login-panel-layout')
@section('pagecontent')
    <div class="col-6 table-container offset-3 text-center">
        <h1><i class="fa fa-calendar" aria-hidden="true"></i> Admin Login Panel</h1>
        </br>
    {{ Form::open(array('url' => 'admin/auth')) }}
    <!-- if there are login errors, show them here -->
        <p>
            {{ $errors->first('email') }}
            {{ $errors->first('password') }}
        </p>

        <div class="row">
            <div class="col-6 text-right">
                {{ Form::label('email', 'Email Address') }}
            </div>
            <div class="col-6 text-left">
                {{ Form::text('email', \Illuminate\Support\Facades\Input::old('email'), array('placeholder' => 'user@email.com')) }}
            </div>
        </div>
        <div class="row">
            <div class="col-6 text-right">
                {{ Form::label('password', 'Password') }}
            </div>
            <div class="col-6 text-left">
                {{ Form::password('password') }}
            </div>
        </div>
    </br>
        <p>{{ Form::submit('Login') }}</p>
        {{ Form::close() }}

    </div>
@endsection
