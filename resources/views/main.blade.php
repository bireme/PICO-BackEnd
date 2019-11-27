@extends('layout.mainlayout')
@section('content')

    <section class="padding1">
        <div class="container">
            @include('partials.PICOelements.PICO')
            <div>
                <h3 class="result text-center">
                    <div class="btn-group">
                        <b>
                            <div>
                                <form method="post"
                                      action="https://pesquisa.bvsalud.org/portal/" id="form-final">
                                    <input class="data-href" name="q" type="hidden" value="">
                                </form>
                                <button type="submit" form="form-final" id="FinalGlobal" class="btn btn-lg btn-success" data-toggle="tooltip"
                                        data-placement="top" title="" data-piconum="5"
                                        data-original-title="{{ __('lang.clickres') }}"><p class="p-0 m-0 label nomargin d-inline-block">{{ __('lang.sres') }}</p><span
                                        id="finalupdated" class="badge badge-light badgeM d-none"
                                        style="margin-left:15px;"></span><span id="finalmustupdate"
                                                                               style="margin-left:15px;"><i
                                            class="fas fa-sync-alt"></i></span></button>
                            </div>
                        </b>
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
