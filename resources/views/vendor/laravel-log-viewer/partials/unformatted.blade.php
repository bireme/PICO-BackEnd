<div class="row">
    <div class="accordion picoaccordion col-12-md" id="AccordionError{{$id}}" style="width:100%;">
        <div class="card h-auto">
            <div class="cardcustomheader h-auto text-center col-md-12" id="itemHeaderError{{$id}}">
                <button class="btn btn-primary collapsed btn-block btn-cardheader btn-logcolor"
                        type="button"
                        style="background-color: orangered"
                        data-toggle="collapse"
                        data-target="#expandableError{{$id}}"
                        aria-expanded="false"
                        aria-controls="expandableError{{$id}}">Unformatted Data</button>
            </div>
            <div id="expandableError{{$id}}" class="collapse "
                 aria-labelledby="itemHeaderError{{$id}}"
                 data-parent="#AccordionError{{$id}}">
                <div class="card-body container" style="padding:0;">
                    <div class="datacontent container-fluid">
                        <div class="row" style="padding-top:0;padding-bottom:15px;">
                            <div class="col-md-12" style="background-color:rgba(45, 62, 80,0.50);color:#000000;padding:0;">Error: {{$error}}</div>
                        </div>
                        @if($content)
                            <div class="row">
                                <div class="col-md-12">{{$content}}</div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

