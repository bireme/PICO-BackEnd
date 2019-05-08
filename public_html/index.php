<!DOCTYPE html>
<!-- saved from url=(0049)http://hotsites.homolog.bvsalud.org/mockups/pico/ -->
<html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <meta name="autor" content="">
        <meta name="keywords" content="">
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>PICO Search</title>
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <link rel="stylesheet" href="style.css">
        <link rel="stylesheet" href="style2.css">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css">
        <link href="https://fonts.googleapis.com/css?family=Anton|Poppins" rel="stylesheet">
    </head><body>
        <header id="header">
            <div id="lang">
                <a id="page-lang1">Português</a>  |  
                <a id="page-lang0">English</a>  |  
                <a id="page-lang2">Español</a>  |  
                <a id="page-lang3">Français</a>  
            </div>
            <div class="container">
                <div class="row">
                    <div class="col-md-4" id="logo">
                        <a href=""><img src="img/BVS.svg" alt="" class="img-fluid"></a>
                    </div>
                    <div class="col-md-8" id="pico">
                        <img src="img/Picos.svg" alt="" class="img-fluid">
                    </div>
                </div>
            </div>
        </header>
        <div class='temp-div-container text-center'>
            <div class='temp-div text-center'>
                <div class='temp-header'>
                    <span>Este elemento es temporal y solo para debug</span>
                </div>
                <div class='temp-info'>
                    <span><a id="debugEventInfo" class="btn btn-primary btn-info">Log de eventos</a></span>
                    <span><a id="debugErrorInfo" class="btn btn-primary btn-info">Log de errores</a></span>
                </div>
            </div>
        </div>
        <section class="padding1">
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
                                    <input type="text" id="datainput1" data-oldVal="" class="form-control" placeholder="Type of patient eg. diabetcs">
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
                                    <a class="btn btn-primary margin2M ExpandDeCS d-none"  id="Exp1" name="Problem">Expand DeSH/MeSH Terms</a>
                                    <div class="btn-group">
                                        <a id="ResNumLocal1" target="_blank" class="btn colorP d-none" data-toggle="tooltip" data-placement="top" title="" data-original-title="Click to see results">Population <span class="badge badge-light badgeM">15.039</span></a>
                                    </div>
                                    <span><a id="CalcRes1" class="btn btn-primary"><label>Results </label><i class="fas fa-redo"></i></a></span>
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
                                    <input type="text" id="datainput2" data-oldVal="" class="form-control" placeholder="Any Intervention eg. treatment, diagnostic test">
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
                                    <a class="btn btn-primary margin2M ExpandDeCS d-none" id="Exp2" name="Intervention" data-toggle="modal" data-target="#modal">Expand DeSH/MeSH Terms</a>
                                    <div class="btn-group">
                                        <a id="ResNumLocal2" target="_blank" class="btn colorI d-none" data-toggle="tooltip" data-placement="top" title="" data-original-title="Click to see results">Intervention <span class="badge badge-light badgeM">350</span></a>
                                        <a id="ResNumGlobal2" target="_blank" class="btn btn-warning d-none" data-toggle="tooltip" data-placement="top" title="" data-original-title="Click to see results">Population AND Intervention <span class="badge badge-light badgeM">150</span></a>
                                    </div>
                                    <span><a id="CalcRes2" class="btn btn-primary"><label>Results </label><i class="fas fa-redo"></i></a></span>
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
                                    <input type="text" id="datainput3" data-oldVal="" class="form-control" placeholder="Comparing your intervention with another treatment">
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
                                    <a class="btn btn-primary margin2M ExpandDeCS d-none" id="Exp3" name="Comparison" data-toggle="modal" data-target="#modal">Expand DeSH/MeSH Terms</a>
                                    <div class="btn-group">
                                        <a id="ResNumLocal3" target="_blank" class="btn colorC d-none" data-toggle="tooltip" data-placement="top" title="" data-original-title="Click to see results">Comparison <span class="badge badge-light badgeM">200</span></a>
                                        <a id="ResNumGlobal3" target="_blank" class="btn btn-warning d-none" data-toggle="tooltip" data-placement="top" title="" data-original-title="Click to see results">Population AND Intervention AND Comparison <span class="badge badge-light badgeM">80</span></a>
                                    </div>
                                    <span><a id="CalcRes3" class="btn btn-primary"><label>Results </label><i class="fas fa-redo"></i></a></span>
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
                                    <input type="text" id="datainput4" data-oldVal="" class="form-control" placeholder="Outcomes interest eg. reduced mortality, fewer exacerbati">
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
                                    <a class="btn btn-primary margin2M ExpandDeCS d-none" id="Exp4" name="Outcome" data-toggle="modal" data-target="#modal">Expand DeSH/MeSH Terms</a>
                                    <div class="btn-group">
                                        <a id="ResNumLocal4" target="_blank" class="btn colorO d-none" data-toggle="tooltip" data-placement="top" title="" data-original-title="Click to see results">Outcomes <span class="badge badge-light badgeM">200</span></a>
                                        <a id="ResNumGlobal4"target="_blank" class="btn btn-warning d-none" data-toggle="tooltip" data-placement="top" title="" data-original-title="Click to see results">Population AND Intervention AND Comparison AND Outcomes <span class="badge badge-light badgeM">80</span></a>

                                    </div>
                                    <span><a id="CalcRes4" class="btn btn-primary"><label>Results </label><i class="fas fa-redo"></i></a></span>
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
                        <input type="hidden" id="datainput5" data-oldVal="" class="form-control">
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
                    <b><a id="ResNumGlobal6" class="btn btn-lg btn-success d-none" data-toggle="tooltip" data-placement="top" title="" target="_blank" data-original-title="Click to see results">Search Results <span class="badge badge-light badgeM">10</span></a></b>
                    <span class="float-right"><a id="CalcRes6" class="btn btn-lg btn-info"><label>Results </label><i class="fas fa-redo"></i></a></span>
                </h3>
            </div>
        </div>


        <div class="container"> <br>
            <label for=""><b class="sdlabel">Search Details</b></label>
            <textarea id="FinalSearchDetails" rows="4" class="form-control" readonly="readonly"></textarea>
        </div>
    </section>

    <footer id="footer" style="border-top: 10px solid #2d3e50; ">
        <div class="container">
            PICOS SEARCH -  All RIGHTS RESERVED
        </div>
    </footer>

    <!---------------------------------- Modal  Parte 1 ---------------------------------->
    <div class="modal fade" id="modal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Select DeCS/MeSH Descriptor</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-block btn-primary" data-toggle="modal" data-target="#modal2" data-dismiss="modal">Continue</button>
                </div>
            </div>

        </div>
    </div>





    <!--------------------------------Modal Parte 2---------------------------------->
    <div class="modal fade" id="modal2" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Select DeCS/MeSH Synonym</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body" style="overflow-y: auto!important; max-height: 100%!important;">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-block btn-primary" data-toggle="modal" data-target="#modal3" data-dismiss="modal">Continue</button>
                </div>
            </div>
        </div>
    </div>





    <!-------------------------------- Modal  Parte 3 ---------------------------------->
    <div class="modal fade" id="modal3" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Free Keywords</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="">Improve Search</label>
                        <textarea name="" id="" cols="30" rows="10" class="form-control" placeholder="Ex: tw:(chicungunha) AND (instance:'regional')"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-block btn-primary" data-dismiss="modal">Continue</button>
                </div>
            </div>
        </div>
    </div>


    <!-------------LOADING LOADING LOADING-------------------------->
    <div class="modal fade" id="modal4" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <div class="spinner-border spinner-border-xl" role="status"></div>
                    <label>Loading...</label>
                </div>
                <div class="modal-footer">
                    <button id='CancelLoading' type="button" class="btn btn-block close">Cancel</button>
                </div>
            </div>
        </div>
    </div>


    <!-------------------------------- Modal  info---------------------------------->
    <div id="modalinfo" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="iconElement"><span></span></div>
                    <div class="infoElement"><span></span></div>
                </div>				
                <div class="modal-body text-center">
                    <span class="InfoText"></span>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-block btn-primary" data-dismiss="modal" aria-label="Close">OK</button>
                </div>
            </div>
        </div>
    </div>
    <script src="js/popper.min.js"></script>
    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script type="text/javascript" src="js/main.js"></script>
    <script src="js/BaseInfo.js"></script>
    <script src="js/start.js"></script>
</body></html>