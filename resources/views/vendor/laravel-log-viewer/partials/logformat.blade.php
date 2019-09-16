@if((!(is_array($decoded))))
    @include('vendor.laravel-log-viewer.partials.unformatted',['id'=>$key.'2','error'=>'decoded log[stack] was not an array','content'=>json_encode($log['stack'])])
@else
    @foreach($decoded as $item => $value)
        @if($item==='data')
            @continue
        @endif
        @if($item==='stack' || $item==='info')
            @if(!(is_string($value)))
                @include('vendor.laravel-log-viewer.partials.unformatted',['id'=>$item.$key.'1','error'=>'Content must be string:','content'=>json_encode($value)])
            @else
                @include('vendor.laravel-log-viewer.partials.logcontent',['item'=>$item,'key'=>$key,'value'=>$value])
            @endif
        @else
            @include('vendor.laravel-log-viewer.partials.unformatted',['id'=>$item.$key.'1','error'=>'Content is not stack, data nor info','content'=>json_encode($value)])
        @endif
    @endforeach
@endif
