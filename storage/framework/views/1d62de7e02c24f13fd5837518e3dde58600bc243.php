<!---------------------------------- Modal  Parte 1 ---------------------------------->
<div class="modal fade" id="modal" tabindex="-1" role="dialog">
    <div class="cache-tmp-class d-none"></div>
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?php echo e(__('lang.seldecs')); ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-block btn-primary" data-toggle="modal" data-target="#modal2" data-dismiss="modal"><?php echo e(__('lang.cont')); ?></button>
            </div>
        </div>

    </div>
</div>





<!--------------------------------Modal Parte 2---------------------------------->
<div class="modal fade" id="modal2" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?php echo e(__('lang.selsyn')); ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body" style="overflow-y: auto!important; max-height: 100%!important;">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-block btn-primary" data-toggle="modal" data-target="#modal3" data-dismiss="modal"><?php echo e(__('lang.cont')); ?></button>
            </div>
        </div>
    </div>
</div>





<!-------------------------------- Modal  Parte 3 ---------------------------------->
<div class="modal fade" id="modal3" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?php echo e(__('lang.free')); ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for=""><?php echo e(__('lang.improve')); ?></label>
                    <textarea name="" id="" cols="30" rows="10" class="form-control" placeholder="<?php echo e(__('lang.impex')); ?>"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-block btn-primary" data-dismiss="modal"><?php echo e(__('lang.cont')); ?></button>
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
                <label><?php echo e(__('lang.load')); ?></label>
            </div>
            <div class="modal-footer">
                <button id='CancelLoading' type="button" class="btn btn-block btn-info"><?php echo e(__('lang.cancel')); ?></button>
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
</div><?php /**PATH C:\xampp\htdocs\Laravel-BIREME\resources\views/layout/partials/modais.blade.php ENDPATH**/ ?>