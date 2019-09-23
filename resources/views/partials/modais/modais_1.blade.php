@extends('layout.modaislayout',['modalId' => 'modal1', 'title' => __('lang.seldecs')])

@section('modal-body')
    <div class="modal-body">
    </div>
@overwrite

@section('modal-footer')
    <div class="col-md-4 text-center">
        <button type="button" class="btn btn-block btn-primary btn-back" data-toggle="modal" data-target="#modal1" data-dismiss="modal">{{ __('lang.back') }}</button>
    </div>
    <div class="col-md-4 text-center offset-2">
        <button type="button" class="btn btn-block btn-primary btn-continue" data-toggle="modal" data-target="#modal1" data-dismiss="modal">{{ __('lang.cont') }}</button>
    </div>
@overwrite
