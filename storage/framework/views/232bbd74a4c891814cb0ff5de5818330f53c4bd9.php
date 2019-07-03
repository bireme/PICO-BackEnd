<?php $__env->startSection('content'); ?>

<section class="padding1">
    <div class="container">
        <div class="container">       
            <li class="d-none"><input type="checkbox" class="languageCheckbox d-none" name="Languages[]" value="en" checked >English</input> </li>
            <li class="d-none"><input type="checkbox" class="languageCheckbox d-none" name="Languages[]" value="es" >Spanish </input> </li>
            <li class="d-none"><input type="checkbox" class="languageCheckbox d-none" name="Languages[]" value="pt" >Portuguese</input>  </li>
        </div>

        <div class="accordion" id="accordionPicos">
            <!-------------------------------------- P -->
            <div class="card">
                <div class="card-header" id="heading1" data-toggle="collapse" data-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                    <h2 class="mb-0">
                        <span class="acordionIcone float-right fas fa-minus"></span>
                        <button class="btn btn-link collapsed labelMain" type="button">
                            Population
                        </button>
                        <a id="PICOinfo1" class="PICOiconElement info-info"><span>i</span></a>
                    </h2>
                </div>
                <div id="collapseOne" class="collapse show" aria-labelledby="heading1">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8 margin2M">
                                <input type="text" id="datainput1" data-query-split=""  data-oldVal="" class="form-control" placeholder="Type of patient eg. diabetcs">
                            </div>
                            <div class="col-md-4">
                                <select name="" id="FieldList1" data-oldVal="" class="form-control formSelect">
                                    <option value="">All fields</option>
                                    <option value="">Title, Abstract, DeSC/MeSH Terms</option>
                                    <option value="">Title</option>
                                    <option value="">DeSC/MeSH Terms</option>
                                </select>
                            </div>
                        </div>
                        <div class="row margin1">
                            <div class="col-12">

                                <button class="btn btn-primary margin2M ExpandDeCS d-none"  id="Exp1" name="Problem">Expand DeSH/MeSH Terms</button>
                                <div class="btn-group">
                                    <a id="ResNumLocal1" target="_blank"  class="btn colorP d-none" data-toggle="tooltip" data-placement="top" title="Click to see results"><label></label> <span class="badge badge-light badgeM">15.039</span></a>
                                    <button id="CalcRes1" class="btn btn-info" data-toggle="tooltip" data-placement="top" title="Update Results">Results</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-------------------------------------- I -->
            <div class="card">
                <div class="card-header" id="heading2" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                    <h2 class="mb-0">
                        <span class="acordionIcone float-right fas fa-plus"></span>
                        <button class="btn btn-link collapsed labelMain" type="button">
                            Intervention
                        </button>
                        <a id="PICOinfo2" class="PICOiconElement info-info"><span>i</span></a>
                    </h2>
                </div>
                <div id="collapseTwo" class="collapse" aria-labelledby="heading2">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8 margin2M">
                                <input type="text" id="datainput2" data-query-split=""  data-oldVal="" class="form-control" placeholder="Any Intervention eg. treatment, diagnostic test">
                            </div>
                            <div class="col-md-4">
                                <select name=""  id="FieldList2" data-oldVal="" class="form-control formSelect">
                                    <option value="">All fields</option>
                                    <option value="">Title, Abstract, DeSC/MeSH Terms</option>
                                    <option value="">Title</option>
                                    <option value="">DeSC/MeSH Terms</option>
                                </select>
                            </div>
                        </div>
                        <div class="row margin1">
                            <div class="col-12">
                                <button class="btn btn-primary margin2M ExpandDeCS d-none" id="Exp2" name="Intervention">Expand DeSH/MeSH Terms</button>
                                <div class="btn-group">
                                    <a id="ResNumLocal2" target="_blank" class="btn colorI  d-none" data-toggle="tooltip" data-placement="top" title="Click to see results"><label></label> <span class="badge badge-light badgeM">350</span></a>
                                    <a id="ResNumGlobal2" target="_blank" class="btn btn-warning  d-none" data-toggle="tooltip" data-placement="top" title="Click to see results"><label>Population AND Intervention </label><span class="badge badge-light badgeM">150</span></a>
                                    <button id="CalcRes2" class="btn btn-info" data-toggle="tooltip" data-placement="top" title="Update Results">Results</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-------------------------------------- C -->
            <div class="card">
                <div class="card-header" id="heading3" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                    <h2 class="mb-0">
                        <span class="acordionIcone float-right fas fa-plus"></span>
                        <button class="btn btn-link collapsed labelMain" type="button">
                            Comparison
                        </button>
                        <a id="PICOinfo3" class="PICOiconElement info-info"><span>i</span></a>
                    </h2>
                </div>
                <div id="collapseThree" class="collapse" aria-labelledby="heading3">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8 margin2M">
                                <input type="text" id="datainput3" data-query-split=""  data-oldVal="" class="form-control" placeholder="Comparing your intervention with another treatment">
                            </div>
                            <div class="col-md-4">
                                <select name=""  id="FieldList3" data-oldVal="" class="form-control formSelect">
                                    <option value="">All fields</option>
                                    <option value="">Title, Abstract, DeSC/MeSH Terms</option>
                                    <option value="">Title</option>
                                    <option value="">DeSC/MeSH Terms</option>
                                </select>
                            </div>
                        </div>
                        <div class="row margin1">
                            <div class="col-12">
                                <button class="btn btn-primary margin2M ExpandDeCS d-none" id="Exp3" name="Comparison">Expand DeSH/MeSH Terms</button>
                                <div class="btn-group">
                                    <a id="ResNumLocal3" target="_blank" class="btn colorC  d-none" data-toggle="tooltip" data-placement="top" title="Click to see results"><label></label> <span class="badge badge-light badgeM">200</span></a>
                                    <a id="ResNumGlobal3" target="_blank" class="btn btn-warning  d-none" data-toggle="tooltip" data-placement="top" title="Click to see results"><label>Population AND Intervention AND Comparison </label><span class="badge badge-light badgeM">80</span></a>
                                    <button id="CalcRes3" class="btn btn-info" data-toggle="tooltip" data-placement="top" title="Update Results">Results</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-------------------------------------- O -->
            <div class="card">
                <div class="card-header" id="heading4" data-toggle="collapse" data-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                    <h2 class="mb-0">
                        <span class="acordionIcone float-right fas fa-plus"></span>
                        <button class="btn btn-link collapsed labelMain" type="button">
                            Outcome
                        </button>
                        <a id="PICOinfo4" class="PICOiconElement info-info"><span>i</span></a>
                    </h2>
                </div>
                <div id="collapseFour" class="collapse" aria-labelledby="heading4">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8 margin2M">
                                <input type="text" id="datainput4" data-query-split=""  data-oldVal="" class="form-control" placeholder="Outcomes interest eg. reduced mortality, fewer exacerbati">
                            </div>
                            <div class="col-md-4">
                                <select name=""  id="FieldList4" data-oldVal="" class="form-control formSelect">
                                    <option value="">All fields</option>
                                    <option value="">Title, Abstract, DeSC/MeSH Terms</option>
                                    <option value="">Title</option>
                                    <option value="">DeSC/MeSH Terms</option>
                                </select>
                            </div>
                        </div>
                        <div class="row margin1">
                            <div class="col-12">
                                <button class="btn btn-primary margin2M ExpandDeCS d-none" id="Exp4" name="Outcome">Expand DeSH/MeSH Terms</button>
                                <div class="btn-group">
                                    <a id="ResNumLocal4" target="_blank" class="btn colorO  d-none" data-toggle="tooltip" data-placement="top" title="Click to see results"><label></label> <span class="badge badge-light badgeM">135</span></a>
                                    <a id="ResNumGlobal4" target="_blank" class="btn btn-warning d-none" data-toggle="tooltip" data-placement="top" title="Click to see results"><label>Population AND Intervention AND Comparison AND Outcomes </label><span class="badge badge-light badgeM">50</span></a>
                                    <button id="CalcRes4" class="btn btn-info " data-toggle="tooltip" data-placement="top" title="Update Results">Results</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-------------------------------------- T -->
            <div class="card">
                <div class="card-header" id="heading5" data-toggle="collapse" data-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                    <h2 class="mb-0">
                        <span class="acordionIcone float-right fas fa-plus"></span>
                        <button class="btn btn-link collapsed labelMain" type="button">
                            Type of Study
                        </button>
                        <a id="PICOinfo5" class="PICOiconElement info-info"><span>i</span></a>
                    </h2>
                </div>
                <div id="collapseFive" class="collapse" aria-labelledby="heading5" data-oldVal="">
                    <input type="hidden" id="datainput5" data-query-split=""  data-oldVal="" class="form-control">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group form-check">
                                    <input type="checkbox" class="form-check-input" id="1">
                                    <label class="form-check-label" for="1">Case report</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group form-check">
                                    <input type="checkbox" class="form-check-input" id="2">
                                    <label class="form-check-label" for="2">Systematic reviews</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group form-check">
                                    <input type="checkbox" class="form-check-input" id="3">
                                    <label class="form-check-label" for="3">Systematic reviews</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group form-check">
                                    <input type="checkbox" class="form-check-input" id="4">
                                    <label class="form-check-label" for="4">Cohort study</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group form-check">
                                    <input type="checkbox" class="form-check-input" id="5">
                                    <label class="form-check-label" for="5">Practice guideline</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group form-check">
                                    <input type="checkbox" class="form-check-input" id="6">
                                    <label class="form-check-label" for="6">Controlled clinical trial</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group form-check">
                                    <input type="checkbox" class="form-check-input" id="7">
                                    <label class="form-check-label" for="7">Health technology assessment</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group form-check">
                                    <input type="checkbox" class="form-check-input" id="8">
                                    <label class="form-check-label" for="8">Overview</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group form-check">
                                    <input type="checkbox" class="form-check-input" id="9">
                                    <label class="form-check-label" for="9">Health economic evaluation</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group form-check">
                                    <input type="checkbox" class="form-check-input" id="10">
                                    <label class="form-check-label" for="10">Evidence synthesis</label>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div>
            <h3 class="result text-center">
                <div class="btn-group">
                    <b><a id="ResNumGlobal6" class="btn btn-lg btn-success" data-toggle="tooltip" data-placement="top" title="" target="_blank" data-original-title="Click to see results"><label class="nomargin">Search Results</label> <span class="badge badge-light badgeM d-none">10</span></a></b>
                    <button id="CalcRes6" class="btn btn-outline-info d-none" data-toggle="tooltip" data-placement="top" title="Update Results"><i class="fas fa-sync-alt"></i></button>
                </div>
            </h3>
        </div>
    </div>


    <div class="container"> <br>
        <label for=""><b class="sdlabel">Search Details</b></label>
        <textarea id="FinalSearchDetails" rows="4" class="form-control" readonly="readonly">Please update the results...</textarea>
    </div>
</section>

<?php $__env->stopSection(); ?>
<b></b>
<?php echo $__env->make('layout.mainlayout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\Laravel-BIREME\resources\views/demo.blade.php ENDPATH**/ ?>