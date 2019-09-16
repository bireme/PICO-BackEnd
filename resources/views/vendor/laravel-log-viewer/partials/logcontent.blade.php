<div class="accordion picoaccordion col-12-md" id="Accordion{{$item}}{{$key}}"
     style="width:100%;padding-bottom:15px;">
    <div class="card h-auto">
        <div class="cardcustomheader h-auto text-center col-md-12" id="itemHeader{{$item}}{{$key}}">
            <button class="btn btn-primary collapsed btn-block btn-cardheader btn-logcolor"
                    type="button"
                    data-toggle="collapse"
                    data-target="#expandable{{$item}}{{$key}}"
                    aria-expanded="false"
                    aria-controls="expandable{{$item}}{{$key}}">{{$item}}</button>
        </div>
        <div id="expandable{{$item}}{{$key}}" class="collapse "
             aria-labelledby="itemHeader{{$item}}{{$key}}"
             data-parent="#Accordion{{$item}}{{$key}}">
            <div class="card-body container">
                <div class="datacontent container-fluid">
                    <div class="row">
                        <div class="col-md-12">{{$value}}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
