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
    </div>
</div>
