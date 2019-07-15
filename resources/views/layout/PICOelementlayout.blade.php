<div class="card">
    @if($PICOiterative===1)
        <?php
        $txt='';
        $txt2=' show';
        ?>
    @else
        <?php
        $txt=' collapsed';
        $txt2='';
        ?>
    @endif
        <div class="card-header{{ $txt }}" id="heading{{ $PICOiterative }}" data-toggle="collapse"
             data-target="#collapse{{ $PICOiterative }}" aria-expanded="false"
             aria-controls="collapse{{ $PICOiterative }}">

            <h2 class="mb-0">
                <span class="acordionIcone float-right fas fa-minus"></span>
                <button class="btn btn-link collapsed labelMain" type="button">
                    {{ __('lang.pico'.$PICOiterative) }}
                </button>
                <a id="PICOinfo{{ $PICOiterative }}" class="PICOiconElement info-info"><span>i</span></a>
            </h2>
        </div>
        <div id="collapse{{ $PICOiterative }}" class="collapse{{ $txt2 }}" aria-labelledby="heading{{ $PICOiterative }}">
            @if($PICOiterative<5)
                @include('partials.PICOelements.PICOelement',['PICOiterative'=>$PICOiterative,'FieldNames'=>$FieldNames,'olddata'=>$olddata])
            @else
                @include('partials.PICOelements.PICOtypeofstudy',['PICOiterative'=>$PICOiterative,'TOS'=>$TOS,'olddata'=>$olddata,'cachetmp'=>$cachetmp, 'TmpCookieElement'=>$TmpCookieElement,'oldTOS'=>$oldTOS])
            @endif
        </div>
</div>
