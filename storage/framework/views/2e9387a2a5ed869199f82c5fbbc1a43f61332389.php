;

<?php $__env->startSection('modal-header'); ?>
    <div class="modal-header">
        <div class="iconElement"><span></span></div>
        <div class="infoElement"><span></span></div>
    </div>
<?php $__env->stopSection(true); ?>

<?php $__env->startSection('modal-body'); ?>
    <span class="InfoText"></span>
<?php $__env->stopSection(true); ?>

<?php $__env->startSection('modal-footer'); ?>
    <button type="button" class="btn btn-block btn-primary" data-dismiss="modal" aria-label="Close">OK</button>
<?php $__env->stopSection(true); ?>

<?php echo $__env->make('layout.secondarymodaislayout',['modalId' => 'modalinfo'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\Laravel-BIREME\resources\views/partials/modais/info.blade.php ENDPATH**/ ?>