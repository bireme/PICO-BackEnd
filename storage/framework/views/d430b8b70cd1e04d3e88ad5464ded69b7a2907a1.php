<?php $__env->startSection('cardcontent'); ?>
    <input type="hidden" id="datainput<?php echo e($PICOiterative); ?>" data-query-split=""  data-oldVal="" class="form-control">
    <div class="card-body">
        <div class="row">
            $count=1;
            <?php $__currentLoopData = $studyElelementsArray; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $studyElement): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-md-4">
                    <div class="form-group form-check">
                        <input type="checkbox" class="form-check-input" id="<?php echo e($count); ?>">
                        <label class="form-check-label" for="<?php echo e($count); ?>">$studyElement</label>
                    </div>
                </div>
                $count++;
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.PICOelementlayout',['PICOiterative' => $PICOiterative], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\Laravel-BIREME\resources\views/layout/PICOtypeofstudy.blade.php ENDPATH**/ ?>
