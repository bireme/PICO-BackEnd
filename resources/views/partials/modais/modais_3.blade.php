@extends('layout.modaislayout',['modalId' => 'modal3', 'title' => __('lang.free') ])

@section('modal-body')
    <div class="modal-body">
        <div class="form-group">
            <label for="">{{ __('lang.improve') }}</label>
            <textarea name="" id="" cols="30" rows="10" class="form-control"
                      placeholder="{{ __('lang.impex') }}"></textarea>
        </div>
    </div>
@overwrite

@section('modal-footer')
    <div class="col-md-4 text-center">
        <button type="button" class="btn btn-block btn-primary btn-back" data-toggle="modal" data-target="#modal3" data-dismiss="modal">{{ __('lang.back') }}</button>
    </div>
    <div class="col-md-4  offset-2 text-center">
        <button type="button" class="btn btn-block btn-primary btn-continue" data-toggle="modal" data-target="#modal3" data-dismiss="modal">{{ __('lang.cont') }}</button>
    </div>
@overwrite
