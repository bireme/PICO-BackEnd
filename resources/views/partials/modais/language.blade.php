@extends('layout.secondarymodaislayout',['modalId' => 'modallanguage','title' => __('lang.info')]);

@section('modal-header')
    <div class="modal-header text-center">
        <div class="iconElement info-config text-center white"><span><i class="fas fa-cog"></i></span></div>
        <div class="text-center"><span class="modal-title">{{__('lang.conf')}}</span></div>
        <button type="hidden" id="closemodallanguage" class="close d-none" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
    </div>
@overwrite

@section('modal-body')
    @spaceless
    <div class="modal-body InfoText">
        <div class="cointainer-fluid">
            <div class="row">
                <div class="offset-md-1 secondary-info col-md-10 text-dark font-italic">
                    <span>{{ __('lang.langimp') }}</span>
                </div>
            </div>
            <div class="row">
                <div class="offset-md-2 col-md-8 span12">
                    <div id="languagenumberalert" class="alert alert-danger alert-dismissible d-none" role="alert">
                        {{ __('lang.minonelang') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="primary-info col-md-12">
                    <div id="LanguageSection" class="container ">
                        <div class="row">
                            <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3 tableModal table-hover">
                                <table style="height:100%">
                                    <tbody>
                                    <tr>
                                        <td><input id="decslanguage1" name="decslanguages[]" class="languageCheckbox"
                                                   type="checkbox"
                                                   checked="" data-lang="en"></td>
                                        <td><label for="decslanguage1">{{ __('lang.langen') }}</label></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3 tableModal table-hover">
                                <table style="height:100%">
                                    <tbody>
                                    <tr>
                                        <td><input id="decslanguage2" name="decslanguages[]" class="languageCheckbox"
                                                   type="checkbox"
                                                   checked="" data-lang="pt"></td>
                                        <td><label for="decslanguage2">{{ __('lang.langpt') }}</label></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3 tableModal table-hover">
                                <table style="height:100%">
                                    <tbody>
                                    <tr>
                                        <td><input id="decslanguage3" name="decslanguages[]" class="languageCheckbox"
                                                   type="checkbox"
                                                   checked="" data-lang="es"></td>
                                        <td><label for="decslanguage3">{{ __('lang.langes') }}</label></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="offset-md-1 secondary-info col-md-10 text-dark font-italic d-none"></div>
            </div>
        </div>
    </div>
    @endspaceless
@overwrite

@section('modal-footer')
    <button type="button" class="btn btn-block btn-primary btn-continue golanguage">{{ __('lang.cont') }}</button>
@overwrite
