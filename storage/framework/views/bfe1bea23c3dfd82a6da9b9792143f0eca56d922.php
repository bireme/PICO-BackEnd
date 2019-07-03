<?php $__env->startSection('content'); ?>

<section class="padding1">
    <div class="container">
        <div class="container">
            <li class="d-none"><input type="checkbox" class="languageCheckbox d-none" name="Languages[]" value="en" checked ><?php echo e(__('lang.langen')); ?></input> </li>
            <li class="d-none"><input type="checkbox" class="languageCheckbox d-none" name="Languages[]" value="es" ><?php echo e(__('lang.langes')); ?> </input> </li>
            <li class="d-none"><input type="checkbox" class="languageCheckbox d-none" name="Languages[]" value="pt" ><?php echo e(__('lang.langpt')); ?></input>  </li>
        </div>

        <?php echo $__env->make('partials.PICO',['PreviousData'=>$PreviousData], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

        <div>
            <h3 class="result text-center">
                <div class="btn-group">
                    <b><a id="ResNumGlobal6" class="btn btn-lg btn-success" data-toggle="tooltip" data-placement="top" title="" target="_blank" data-original-title="<?php echo e(__('lang.clickres')); ?>"><label class="nomargin"><?php echo e(__('lang.sres')); ?></label> <span class="badge badge-light badgeM d-none">10</span></a></b>
                    <button id="CalcRes6" class="btn btn-outline-info d-none" data-toggle="tooltip" data-placement="top" title="<?php echo e(__('lang.upres')); ?>"><i class="fas fa-sync-alt"></i></button>
                </div>
            </h3>
        </div>
    </div>


    <div class="container"> <br>
        <label for=""><b class="sdlabel"><?php echo e(__('lang.sdetails')); ?></b></label>
        <textarea id="FinalSearchDetails" rows="4" class="form-control" readonly="readonly"><?php echo e(__('lang.pleaseupd')); ?></textarea>
    </div>
</section>
<b></b>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.mainlayout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\Laravel-BIREME\resources\views/main.blade.php ENDPATH**/ ?>