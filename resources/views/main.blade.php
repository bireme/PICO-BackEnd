@extends('layout.mainlayout')
@section('content')

    <section class="padding1">
        <div class="container">
            @include('partials.PICOelements.PICO')
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
