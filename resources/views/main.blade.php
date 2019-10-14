@extends('layout.mainlayout')
@section('content')

    <section class="padding1">
        <div class="container">
            @include('partials.PICOelements.PICO')
            <div>
                <h3 class="result text-center">
                    <div class="btn-group">
                        <b><button id="FinalGlobal" class="btn btn-lg btn-success" data-toggle="tooltip"
                              data-placement="top" title="" target="_blank" data-piconum="5"
                              data-original-title="{{ __('lang.clickres') }}"><label class="nomargin">{{ __('lang.sres') }}</label><span
                                    id="finalupdated" class="badge badge-light badgeM d-none" style="margin-left:15px;"></span><span id="finalmustupdate" style="margin-left:15px;"><i class="fas fa-sync-alt"></i></span></button></b>
                    </div>
                </h3>
            </div>
        </div>
        <div class="container"><br>
            <input hidden id="ResNumGlobal5" data-comparison="">
            <label for=""><b class="sdlabel">{{ __('lang.sdetails') }}</b></label>
            <textarea id="FinalSearchDetails" rows="4" class="form-control"
                      readonly="readonly">{{ __('lang.pleaseupd') }}</textarea>
        </div>
    </section>
    <b></b>
@endsection
