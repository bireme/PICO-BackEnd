@extends('layout.modaislayout',['modalId' => 'modal2', 'title' => __('lang.selsyn') ])

@section('modal-body')
    <div class="modal-body" style="overflow-y: auto!important; max-height: 100%!important;">
    </div>
@overwrite

@section('modal-footer')
    <button type="button" class="btn btn-block btn-primary" data-toggle="modal" data-target="#modal2" data-dismiss="modal">{{ __('lang.cont') }}</button>
@overwrite
