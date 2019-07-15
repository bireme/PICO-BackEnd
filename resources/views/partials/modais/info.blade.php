@extends('layout.secondarymodaislayout',['modalId' => 'modalinfo']);

@section('modal-header')
    <div class="modal-header">
        <div class="iconElement"><span></span></div>
        <div class="infoElement"><span></span></div>
    </div>
@overwrite

@section('modal-body')
    <span class="InfoText"></span>
@overwrite

@section('modal-footer')
    <button type="button" class="btn btn-block btn-primary" data-dismiss="modal" aria-label="Close">OK</button>
@overwrite
