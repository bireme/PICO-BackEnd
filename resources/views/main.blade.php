@extends('layout.mainlayout')
@section('content')

<section class="padding1">
    <div class="container">
        <div class="container">
            <li class="d-none"><input type="checkbox" class="languageCheckbox d-none" name="Languages[]" value="en" checked >{{ __('lang.langen') }}</input> </li>
            <li class="d-none"><input type="checkbox" class="languageCheckbox d-none" name="Languages[]" value="es" >{{ __('lang.langes') }} </input> </li>
            <li class="d-none"><input type="checkbox" class="languageCheckbox d-none" name="Languages[]" value="pt" >{{ __('lang.langpt') }}</input>  </li>
        </div>

        @include('partials.PICOelements.PICO',['PreviousData'=>$PreviousData??array()])

        <div>
            <h3 class="result text-center">
                <div class="btn-group">
                    <b><a id="ResNumGlobal6" class="btn btn-lg btn-success" data-toggle="tooltip" data-placement="top" title="" target="_blank" data-original-title="{{ __('lang.clickres') }}"><label class="nomargin">{{ __('lang.sres') }}</label> <span class="badge badge-light badgeM d-none">10</span></a></b>
                    <button id="CalcRes6" class="btn btn-outline-info d-none" data-toggle="tooltip" data-placement="top" title="{{ __('lang.upres') }}"><i class="fas fa-sync-alt"></i></button>
                </div>
            </h3>
        </div>
    </div>


    <div class="container"> <br>
        <label for=""><b class="sdlabel">{{ __('lang.sdetails') }}</b></label>
        <textarea id="FinalSearchDetails" rows="4" class="form-control" readonly="readonly">{{ __('lang.pleaseupd') }}</textarea>
    </div>
</section>
<b></b>
@endsection
