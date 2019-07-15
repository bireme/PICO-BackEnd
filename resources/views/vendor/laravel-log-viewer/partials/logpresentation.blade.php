@if(!($logstack))
    @include('vendor.laravel-log-viewer.partials.unformatted',['id'=>$key.'1','error'=>'log[stack] is empty. This log item does not have a line break','content'=>''])
@else
    @if((!(is_string($logstack))))
        @include('vendor.laravel-log-viewer.partials.unformatted',['id'=>$key.'2','error'=>'log[stack] must be a string that contains the data encoded in JSON','content'=>json_encode($logstack)])
    @else
        <?php $count = 0;
        $itemArray = ['data' => 'Data Content', 'info' => 'General Info', 'stack' => 'Stack Report'];
        ?>

        @spaceless
        @foreach(json_decode($logstack,true)??((is_array($logstack))?($logstack):([])) as $item => $value)

            <?php $count++;
            if (in_array((string)$item, array_keys($itemArray))) {
                $title = $itemArray[(string)$item];
            } else {
                $title = 'Unformatted Data: [' . (string)$item. ']';
            }
            ?>
            @if($value && $value!==null)
                <div class="accordion picoaccordion col-12-md" id="Accordion{{$item}}{{$key}}" style="width:100%;padding-bottom:15px;">
                    <div class="card h-auto">
                        <div class="cardcustomheader h-auto text-center col-md-12" id="itemHeader{{$item}}{{$key}}">
                            <button class="btn btn-primary collapsed btn-block btn-cardheader btn-logcolor"
                                    type="button"
                                    data-toggle="collapse"
                                    data-target="#expandable{{$item}}{{$key}}"
                                    aria-expanded="false"
                                    aria-controls="expandable{{$item}}{{$key}}">{{$title}}</button>
                        </div>
                        <div id="expandable{{$item}}{{$key}}" class="collapse "
                             aria-labelledby="itemHeader{{$item}}{{$key}}"
                             data-parent="#Accordion{{$item}}{{$key}}">
                            <div class="card-body container">
                                <div class="datacontent container-fluid">
                                    <div class="row">
                                        @include('vendor.laravel-log-viewer.partials.content',['id'=>$item.$key,'item'=>$item,'content'=>$value])
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @endforeach
        @endspaceless
        @if($count === 0)
            @include('vendor.laravel-log-viewer.partials.unformatted',['id'=>$key.'3','error'=>'>log[stack]  could not be JSON decoded','content'=>json_encode($logstack)])
        @endif
    @endif
@endif
