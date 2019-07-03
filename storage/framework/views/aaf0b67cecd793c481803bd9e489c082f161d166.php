<div class="modal fade" id="<?php echo e($modalId); ?>" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?php echo e($title); ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>

            <?php echo $__env->yieldContent('modal-body'); ?>

            <div class="modal-footer">
                <?php echo $__env->yieldContent('modal-footer'); ?>
            </div>
        </div>

    </div>
</div>
<?php /**PATH C:\xampp\htdocs\PICO-BackEnd\resources\views/layout/modaislayout.blade.php ENDPATH**/ ?>