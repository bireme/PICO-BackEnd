@extends('layout.secondarymodaislayout',['modalId' => 'modalinfo','title' => __('lang.info')]);

@section('modal-header')
    <div class="modal-header text-center">
        <div class="iconElement"><span></span></div>
        <div class="text-center"><span class="modal-title"></span></div>
        <button type="button" id="closemodalinfo" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
    </div>
@overwrite

@section('modal-body')
    <div class="modal-body InfoText">
        <div class="cointainer-fluid">
            <div class="row">
                <div class="primary-info col-md-12">

                </div>
            </div>
            <div class="row">
                <div class="offset-md-1 secondary-info col-md-10 text-dark font-italic">
                </div>
            </div>
        </div>
    </div>
@overwrite

@section('modal-footer')
    <button type="button" class="btn btn-block btn-primary btn-continue" class="close" data-dismiss="modal" aria-label="Close">{{ __('lang.cont') }}</button>
@overwrite
