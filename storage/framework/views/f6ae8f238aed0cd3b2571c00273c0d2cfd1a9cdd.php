<div class="card">
    <?php if($PICOiterative===1): ?>
        <?php
        $txt='';
        $txt2=' show';
        ?>
    <?php else: ?>
        <?php
        $txt=' collapsed';
        $txt2='';
        ?>
    <?php endif; ?>
        <div class="card-header<?php echo e($txt); ?>" id="heading<?php echo e($PICOiterative); ?>" data-toggle="collapse"
             data-target="#collapse<?php echo e($PICOiterative); ?>" aria-expanded="false"
             aria-controls="collapse<?php echo e($PICOiterative); ?>">

            <h2 class="mb-0">
                <span class="acordionIcone float-right fas fa-minus"></span>
                <button class="btn btn-link collapsed labelMain" type="button">
                    <?php echo e(__('lang.pico'.$PICOiterative)); ?>

                </button>
                <a id="PICOinfo<?php echo e($PICOiterative); ?>" class="PICOiconElement info-info"><span>i</span></a>
            </h2>
        </div>
        <div id="collapse<?php echo e($PICOiterative); ?>" class="collapse<?php echo e($txt2); ?>" aria-labelledby="heading<?php echo e($PICOiterative); ?>">
            <?php if($PICOiterative<5): ?>
                <?php echo $__env->make('partials.PICOelements.PICOelement',['PICOiterative'=>$PICOiterative,'FieldNames'=>$FieldNames,'olddata'=>$olddata], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <?php else: ?>
                <?php echo $__env->make('partials.PICOelements.PICOtypeofstudy',['PICOiterative'=>$PICOiterative,'TOS'=>$TOS,'olddata'=>$olddata, 'TmpCookieElement'=>$TmpCookieElement,'oldTOS'=>$oldTOS], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <?php endif; ?>
        </div>
</div>
<?php /**PATH C:\xampp\htdocs\PICO-BackEnd\resources\views/layout/PICOelementlayout.blade.php ENDPATH**/ ?>