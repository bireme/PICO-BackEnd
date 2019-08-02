<input type="hidden" id="datainput<?php echo e($PICOiterative); ?>" data-query-split="<?php echo e((!!($olddata) ? $olddata['querysplit'] : '')); ?>" data-oldVal="<?php echo e((!!($olddata) ? $olddata['oldval'] : '')); ?>" class="form-control" value="<?php echo e((!!($olddata) ? $olddata['query'] : '')); ?>">
<div class="card-body">
    <div class="row">
        <?php $__currentLoopData = $TOS; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $studyElement): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="col-md-4">
                <div class="form-group form-check">
                    <input type="checkbox" class="form-check-input" id="<?php echo e($loop->iteration); ?>"
                    <?php if(in_array($studyElement,$oldTOS)): ?>
                       checked
                    <?php endif; ?>
                    >
                    <label class="form-check-label" for="<?php echo e($loop->iteration); ?>"><?php echo e($studyElement); ?></label>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>
<?php /**PATH C:\xampp\htdocs\PICO-BackEnd\resources\views/partials/PICOelements/PICOtypeofstudy.blade.php ENDPATH**/ ?>