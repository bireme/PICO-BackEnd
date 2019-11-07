<div class="card-body">
    <div class="row">
        <div class="col-md-8 margin2M">
            <input type="text" data-olddescriptors="" data-PICO="{{ $PICOiterative }}" id="datainput{{ $PICOiterative }}" data-improve="" data-previous-decs=""  data-previous-improve-query=""
                   data-oldVal="" class="form-control PICOchangeitem" placeholder="" value="" />
        </div>
        <div class="col-md-4">
            <select name="" class="form-control black PICOchangeitem" data-PICO="{{ $PICOiterative }}" id="FieldList{{ $PICOiterative }}" data-oldVal="" class="form-control formSelect">
                @foreach($FieldNames as $FieldName)
                    <option class="PICOchangeitem" data-PICO="{{ $PICOiterative }}" value="{{ $FieldName }}"
                        @if ( $loop->iteration=== 0) )
                         selected
                        @endif
                        >{{ $FieldName }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="row margin1">
        <div class="col-12">
            <button class="btn btn-primary margin2M ExpandDeCS"
                    id="Exp{{ $PICOiterative }}">{{ __('lang.butexp') }}<span class="badge badge-light badgeM startlanguage" style="margin-left:15px;"></span></button>
            <div class="btn-group">
                <a id="ResNumLocal{{ $PICOiterative }}" target="_blank" class="btn colorO d-none ResNumBtn" data-href=""
                   data-toggle="tooltip" data-placement="top" title="{{ __('lang.clickres') }}"><label></label>
                    <span class="badge badge-light badgeM" style="margin-left:15px;"></span></a>

                @if($PICOiterative>1)
                    <a id="ResNumGlobal{{ $PICOiterative }}" target="_blank" class="btn colorP d-none ResNumBtn"
                       data-toggle="tooltip" data-placement="top" data-href=""
                       title="{{ __('lang.clickres') }}"><label></label><span
                            class="badge badge-light badgeM" style="margin-left:15px;"></span></a>
                @endif
                <button id="CalcRes{{ $PICOiterative }}" data-piconum="{{ $PICOiterative }}" class="calcresbut btn btn-info" data-toggle="tooltip"
                        data-placement="top" title="{{ __('lang.upres') }}">{{ __('lang.butres') }}</button>
            </div>
        </div>
    </div>
</div>
