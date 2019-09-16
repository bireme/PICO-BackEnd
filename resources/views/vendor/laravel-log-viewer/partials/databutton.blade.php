@if(!($value))
    <span>-</span>
@else
    @if(is_string($value))
        <button class="btn btn-primary exploredd btn-block" name="data" data-content="{{json_encode(['UnnamedString' =>$value])}}">Data</button>
    @else
        <button class="btn btn-primary exploredd btn-block" name="data" data-content="{{json_encode($value)}}">Data</button>
    @endif
@endif
