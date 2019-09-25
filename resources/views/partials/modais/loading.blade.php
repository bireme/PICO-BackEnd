@extends('layout.terciarymodaislayout',['modalId' => 'modal4'])

@section('modal-header')
@overwrite

@section('modal-body')
    <div class="spinner-border spinner-border-xl" role="status"></div>
    <label>{{ __('lang.load') }}</label>
@overwrite

@section('modal-footer')
    <button id='CancelLoading' type="button" class="btn btn-block btn-info">{{ __('lang.cancel') }}</button>
@overwrite
