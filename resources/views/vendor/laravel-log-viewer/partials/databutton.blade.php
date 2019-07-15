<div class="col-md-4" style="display:flex;justify-content:center;align-items: center;flex-flow: column;margin-bottom:15px">
    @if((!($data)))
        <button class="btn btn-secondary btn-disabled btn-block">{{$title}} = null;</button>
    @else
        <button class="btn btn-primary exploredd btn-block" name="{{$title}}" data-content="{{json_encode($data)}}">Click to see {{$title}}</button>
    @endif
</div>
