<input type="hidden" id="datainput{{ $PICOiterative }}" data-query-split="{{ (!!($olddata) ? $olddata['querysplit'] : '') }}" data-oldVal="{{ (!!($olddata) ? $olddata['oldval'] : '')  }}" class="form-control" value="{{ (!!($olddata) ? $olddata['query'] : '')  }}">
<div class="card-body">
    <div class="row">
        @foreach($TOS as $studyElement)
            <div class="col-md-4">
                <div class="form-group form-check">
                    <input type="checkbox" class="form-check-input" id="{{ $loop->iteration }}"
                    @if (in_array($studyElement,$oldTOS))
                       checked
                    @endif
                    >
                    <label class="form-check-label" for="{{ $loop->iteration }}">{{ $studyElement }}</label>
                </div>
            </div>
        @endforeach
    </div>
</div>
