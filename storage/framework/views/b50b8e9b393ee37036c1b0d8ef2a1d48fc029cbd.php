<?php $__env->startSection('modal-body'); ?>
    <div class="modal-body">
    </div>
<?php $__env->stopSection(true); ?>

<?php $__env->startSection('modal-footer'); ?>
    <button type="button" class="btn btn-block btn-primary" data-toggle="modal" data-target="#modal1" data-dismiss="modal"><?php echo e(__('lang.cont')); ?></button>
<?php $__env->stopSection(true); ?>


<?php echo $__env->make('layout.modaislayout',['modalId' => 'modal1', 'title' => __('lang.seldecs')], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\PICO-BackEnd\resources\views/partials/modais/modais_1.blade.php ENDPATH**/ ?>