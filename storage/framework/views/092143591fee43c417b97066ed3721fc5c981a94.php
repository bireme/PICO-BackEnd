<?php $__env->startSection('modal-header'); ?>
<?php $__env->stopSection(true); ?>

<?php $__env->startSection('modal-body'); ?>
    <div class="spinner-border spinner-border-xl" role="status"></div>
    <label><?php echo e(__('lang.load')); ?></label>
<?php $__env->stopSection(true); ?>

<?php $__env->startSection('modal-footer'); ?>
    <button id='CancelLoading' type="button" class="btn btn-block btn-info"><?php echo e(__('lang.cancel')); ?></button>
<?php $__env->stopSection(true); ?>

<?php echo $__env->make('layout.secondarymodaislayout',['modalId' => 'modal4'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\Laravel-BIREME\resources\views/partials/modais/loading.blade.php ENDPATH**/ ?>