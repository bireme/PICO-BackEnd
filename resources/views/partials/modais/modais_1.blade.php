@extends('layout.modaislayout',['modalId' => 'modal1', 'title' => __('lang.seldecs')])

@section('modal-body')
    <div class="modal-body" style="height: 400px;overflow-y: auto;">
    </div>
@overwrite

@section('modal-footer')
    <div class="col-md-4 text-center">
        <button type="button" class="btn btn-block btn-cancel btn-danger" class="close" data-dismiss="modal" aria-label="Close">{{ __('lang.back') }}</button>
    </div>
    <div class="offset-md-4 col-md-4 text-center offset-2">
        <button type="button" class="btn btn-block btn-primary btn-continue" data-toggle="modal" data-target="#modal1" data-dismiss="modal">{{ __('lang.cont') }}</button>
    </div>
@overwrite
