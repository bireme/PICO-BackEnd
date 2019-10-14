<div class="row align-middle" style="color:#2d3e50">
    <div class="col-md-12 text-center align-middle">
        <div class="container-fluid">
            @if(is_string($result))
                @include('vendor.laravel-log-viewer.partials.StringInDebugEncoded',['string'=>$result])
            @elseif(is_array($result))
                <div class="col-md-10">
                    <div class="container-fluid">
                        @foreach($result as $item =>$content)
                            <hr style="height:3px;" class="btn-dark btn-outline-dark">
                            <div class="row text-left">
                                <p class="h6">{{$item}}</p><br>
                            </div>
                            <div class="row text-left">
                                @if(is_string($content))
                                    @include('vendor.laravel-log-viewer.partials.StringInDebugEncoded',['string'=>$content])
                                @elseif(is_array($content))
                                    <div class="col-md-10">
                                        <div class="container-fluid">
                                            @foreach($content as $subitem =>$subcontent)
                                                <hr style="height:3px;" class="btn-dark btn-outline-dark">
                                                <div class="row text-left">
                                                    <p class="h6">{{$subitem}}</p><br>
                                                </div>
                                                <div class="row text-left">
                                                    @if(is_string($subcontent))
                                                        @include('vendor.laravel-log-viewer.partials.StringInDebugEncoded',['string'=>$subcontent])
                                                    @else
                                                        <?php
                                                        dump($subcontent)
                                                        ?>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @else
                                    <?php
                                    dump($content)
                                    ?>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <?php
                dump($result)
                ?>
            @endif
        </div>
    </div>
</div>
</div>
<footer id="footer">
    <div class="d-none">loaded</div>
</footer>
</body>
</html>

