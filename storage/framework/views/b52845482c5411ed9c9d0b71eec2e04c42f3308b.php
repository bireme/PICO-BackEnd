<div class="card-body">
    <div class="row">
        <div class="col-md-8 margin2M">
            <input type="text" id="datainput<?php echo e($PICOiterative); ?>" data-query-split="<?php echo e((!!($olddata) ? $olddata['querysplit'] : '')); ?>"
                   data-oldVal="<?php echo e((!!($olddata) ? $olddata['oldval'] : '')); ?>"
                   class="form-control" placeholder="<?php echo e(__('lang.pico_ex'.$PICOiterative)); ?>"
                   value="<?php echo e((!!($olddata) ? $olddata['query'] : '')); ?>" />
        </div>
        <div class="col-md-4">
            <?php
            $selval = (int)(!!($olddata) ? $olddata['fieldselection'] : 0);
            ?>
            <select name="" id="FieldList<?php echo e($PICOiterative); ?>" data-oldVal="<?php echo e((!!($olddata) ? $olddata['fieldoldval'] : '')); ?>" class="form-control formSelect">
                <?php $__currentLoopData = $FieldNames; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $FieldName): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($FieldName); ?>"
                        <?php if( $loop->iteration=== $selval): ?> )
                         selected
                        <?php endif; ?>
                        ><?php echo e($FieldName); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
    </div>
    <div class="row margin1">
        <div class="col-12">
            <button class="btn btn-primary margin2M ExpandDeCS d-none"
                    id="Exp<?php echo e($PICOiterative); ?>"><?php echo e(__('lang.butexp')); ?></button>
            <div class="btn-group">
                <a id="ResNumLocal<?php echo e($PICOiterative); ?>" target="_blank" class="btn colorP d-none"
                   data-toggle="tooltip" data-placement="top" title="<?php echo e(__('lang.clickres')); ?>"><label></label>
                    <span class="badge badge-light badgeM"></span></a>

                <?php if($PICOiterative>1): ?>
                    <a id="ResNumGlobal<?php echo e($PICOiterative); ?>" target="_blank" class="btn btn-warning  d-none"
                       data-toggle="tooltip" data-placement="top"
                       title="<?php echo e(__('lang.clickres')); ?>"><label></label><span
                            class="badge badge-light badgeM"></span></a>
                <?php endif; ?>
                <button id="CalcRes<?php echo e($PICOiterative); ?>" class="btn btn-info" data-toggle="tooltip"
                        data-placement="top" title="<?php echo e(__('lang.upres')); ?>"><?php echo e(__('lang.butres')); ?></button>
            </div>
        </div>
    </div>
</div>
<?php /**PATH C:\xampp\htdocs\Laravel-BIREME\resources\views/partials/PICOelements/PICOelement.blade.php ENDPATH**/ ?>