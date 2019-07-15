<?php $__env->startSection('cardcontent'); ?>
    <div class="card-body">
        <div class="row">
            <div class="col-md-8 margin2M">
                <input type="text" id="datainput<?php echo e($PICOiterative); ?>" data-query-split="" data-oldVal=""
                       class="form-control" placeholder="<?php echo e($inputPlaceholder); ?>" />
            </div>
            <div class="col-md-4">
                <select name="" id="FieldList<?php echo e($PICOiterative); ?>" data-oldVal="" class="form-control formSelect">
                    <option value=""><?php echo e(__('lang.fields1')); ?></option>
                    <option value=""><?php echo e(__('lang.fields2')); ?></option>
                    <option value=""><?php echo e(__('lang.fields3')); ?></option>
                    <option value=""><?php echo e(__('lang.fields4')); ?></option>
                </select>
            </div>
        </div>
        <div class="row margin1">
            <div class="col-12">
                <button class="btn btn-primary margin2M ExpandDeCS d-none"
                        id="Exp<?php echo e($PICOiterative); ?>"><?php echo e(__('lang.butexp')); ?></button>
                <div class="btn-group">
                    <a id="ResNumLocal<?php echo e($PICOiterative); ?>" target="_blank" class="btn colorP d-none"
                       data-toggle="tooltip" data-placement="top" title="<?php echo e(__('lang.clickres')); ?>"><label></label>
                        <span class="badge badge-light badgeM"></span></a>

                    <?php if($PICOiterative>1): ?>
                        <a id="ResNumGlobal<?php echo e($PICOiterative); ?>" target="_blank" class="btn btn-warning  d-none"
                           data-toggle="tooltip" data-placement="top"
                           title="<?php echo e(__('lang.clickres')); ?>"><label></label><span
                                class="badge badge-light badgeM"></span></a>
                    <?php endif; ?>
                    <button id="CalcRes<?php echo e($PICOiterative); ?>" class="btn btn-info" data-toggle="tooltip"
                            data-placement="top" title="<?php echo e(__('lang.upres')); ?>"><?php echo e(__('lang.butres')); ?></button>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.PICOelementlayout',['PICOiterative' => $PICOiterative], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\Laravel-BIREME\resources\views/layout/PICOelement.blade.php ENDPATH**/ ?>
