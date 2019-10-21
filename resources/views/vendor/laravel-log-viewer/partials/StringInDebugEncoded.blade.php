@if(is_string($string))
    @php($data=json_decode($string,true)??null)
    @if($data===null)
        @include('vendor.laravel-log-viewer.partials.FinalStringInDebugEncoded',['finalstring'=>$string])
    @else
        decoded
        <hr style="height:3px;" class="btn-dark btn-outline-dark">
        @if(is_string($data))
            @include('vendor.laravel-log-viewer.partials.FinalStringInDebugEncoded',['finalstring'=>$data])
        @elseif(is_array($data))
            <div class="col-md-10">
                <div class="container-fluid">
                    @foreach($data as $subitem =>$subcontent)
                        <hr style="height:3px;" class="btn-dark btn-outline-dark">
                        <div class="row text-left">
                            <p class="h6">{{$subitem}}</p><br>
                        </div>
                        <div class="row text-left">
                            @if(is_string($subcontent))
                                @include('vendor.laravel-log-viewer.partials.FinalStringInDebugEncoded',['finalstring'=>$subcontent])
                            @else
                                <?php
                                dump($subcontent);
                                ?>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            @php(dump($data))
        @endif

    @endif
@else
    not string in encodeddebug
@endif
