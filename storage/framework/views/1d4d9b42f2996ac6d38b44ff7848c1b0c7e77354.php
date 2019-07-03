<?php $__env->startSection('modal-body'); ?>
    <div class="modal-body" style="overflow-y: auto!important; max-height: 100%!important;">
    </div>
<?php $__env->stopSection(true); ?>

<?php $__env->startSection('modal-footer'); ?>
    <button type="button" class="btn btn-block btn-primary" data-toggle="modal" data-target="#modal2" data-dismiss="modal"><?php echo e(__('lang.cont')); ?></button>
<?php $__env->stopSection(true); ?>

<?php echo $__env->make('layout.modaislayout',['modalId' => 'modal2', 'title' => __('lang.selsyn') ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\Laravel-BIREME\resources\views/partials/modais/modais_2.blade.php ENDPATH**/ ?>