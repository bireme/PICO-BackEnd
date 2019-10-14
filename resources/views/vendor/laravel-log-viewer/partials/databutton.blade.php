@if(!($value))
    <span>-</span>
@else
    @if(is_string($value))
        <button class="btn btn-primary exploredd btn-block" name="data"
                data-content="{{json_encode(['UnnamedString' =>$value])}}">Data
        </button>
    @else
        <button class="btn btn-primary exploredd btn-block" name="data" data-content="{{json_encode($value)}}">Data
        </button>
    @endif
    @if($current_folder==='Performance')
        @if($InputData = ($value['currentData']['current.InputOutput']['Input'] ?? null))
        @endif
        @if($InputData!==null)
            <button class="btn btn-primary exploreinput btn-block" name="data" data-content="{{json_encode(['title'=>$title,'input'=>$InputData])}}">Request With Input</button>
        @endif
    @endif
@endif
