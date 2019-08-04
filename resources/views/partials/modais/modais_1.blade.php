@extends('layout.modaislayout',['modalId' => 'modal1', 'title' => __('lang.seldecs')])

@section('modal-body')
    <div class="modal-body">
    </div>
@overwrite

@section('modal-footer')
    <button type="button" class="btn btn-block btn-primary" data-toggle="modal" data-target="#modal2" data-dismiss="modal">{{ __('lang.cont') }}</button>
@overwrite
