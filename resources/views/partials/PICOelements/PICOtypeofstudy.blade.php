<div class="card-body">
    <input type="hidden" data-olddescriptors="" data-PICO="5" id="datainput5" data-improve="" data-previous-decs=""  data-previous-improve-query=""
           data-oldVal="" class="form-control PICOchangeitem" value="" />

    <div class="row">
        @foreach($TOS as $studyElement)
            <div class="col-md-4">
                <div class="form-group form-check">
                    <input type="checkbox" name="{{ $studyElement }}" class="studytypecheckbox form-check-input" id="{{ $loop->iteration }}">
                    <label class="form-check-label" for="{{ $loop->iteration }}">{{ $studyElement }}</label>
                </div>
            </div>
        @endforeach

        <div>
            <h3 class="result text-center">
                <div class="btn-group">
                    <b>
                        <div>
                            <form method="post"
                                  action="https://pesquisa.bvsalud.org/portal/" id="form-final">
                                <input class="data-href" name="q" type="hidden" value="">
                            </form>
                            <button id="FinalGlobal" class="btn btn-info" data-placement="top" title="" data-piconum="5"
                                    data-original-title="{{ __('lang.clickres') }}"><p class="p-0 m-0 label nomargin d-inline-block">{{ __('lang.butres') }}</p></button>

                            <button type="submit" form="form-final" id="PICOSButton" class="btn colorP d-none" data-toggle="tooltip"
                                    data-placement="top" title="" data-piconum="5"
                                    data-original-title="{{ __('lang.clickres') }}"><p class="p-0 m-0 label nomargin d-inline-block">P+I+C+O+S ({{ __('lang.pico5') }})</p><span
                                    id="finalupdated" class="badge badge-light badgeM d-none"
                                    style="margin-left:15px;"></span></button>

                        </div>
                    </b>
                </div>
            </h3>
        </div>



    </div>
</div>
