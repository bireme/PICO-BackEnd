@extends('layout.modaislayout',['modalId' => 'modalkw', 'title' => 'KeywordManager'])

@section('modal-body')
    <div class="modal-body">
    </div>
@overwrite

@section('modal-footer')
    <div class="col-md-4 text-center offset-4">
        <button type="button" class="btn btn-block btn-primary keywordstodecs" data-toggle="modal" data-dismiss="modal">{{ __('lang.cont') }}</button>
    </div>
@overwrite
