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
    <button type="button" class="btn btn-block btn-primary" data-dismiss="modal3">{{ __('lang.cont') }}</button>
@overwrite
