<?php $__env->startSection('modal-body'); ?>
    <div class="modal-body">
        <div class="form-group">
            <label for=""><?php echo e(__('lang.improve')); ?></label>
            <textarea name="" id="" cols="30" rows="10" class="form-control"
                      placeholder="<?php echo e(__('lang.impex')); ?>"></textarea>
        </div>
    </div>
<?php $__env->stopSection(true); ?>

<?php $__env->startSection('modal-footer'); ?>
    <button type="button" class="btn btn-block btn-primary" data-dismiss="modal3"><?php echo e(__('lang.cont')); ?></button>
<?php $__env->stopSection(true); ?>

<?php echo $__env->make('layout.modaislayout',['modalId' => 'modal3', 'title' => __('lang.free') ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\Laravel-BIREME\resources\views/partials/modais/modais_3.blade.php ENDPATH**/ ?>