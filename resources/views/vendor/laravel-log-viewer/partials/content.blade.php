@if($item!=='data')
    @if(is_string($content))
        <div class="col-md-12">{{$content}}</div>
    @else
        <div class="col-md-12">
            @include('vendor.laravel-log-viewer.partials.unformatted',['id'=>$id.'1','error'=>'Stack Content must be string:','content'=>json_encode($content)])
        </div>
    @endif
@else
    @if(((is_string($content))))
        @include('vendor.laravel-log-viewer.partials.unformatted',['id'=>$id.'2','error'=>'Content must be a string that contains the data encoded in JSON:','content'=>json_encode($content)])
    @else
        @foreach($content as $datakey => $value)
                @if($datakey==='AdvancedLoggerData')
                    @if(is_array($value))
                        @foreach($value as $datakeytwo => $datacontent)
                            @include('vendor.laravel-log-viewer.partials.databutton',['title'=>$datakeytwo,'data'=>$datacontent])
                        @endforeach
                    @else
                        @include('vendor.laravel-log-viewer.partials.unformatted',['id'=>($id.$datakey.'1'),'error'=>'AdvancedLoggerData must be array with keys','content'=>json_encode(['AdvancedLoggerData'=>$value])])
                    @endif
                @else
                    @include('vendor.laravel-log-viewer.partials.databutton',['title'=>$datakey,'data'=>$value])
                @endif
        @endforeach
    @endif
@endif

