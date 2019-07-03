<div class="accordion" id="accordionPicos">

    <?php
    use \PICOExplorer\Http\Controllers\SearchFieldsRepositoryController;
    use \PICOExplorer\Http\Controllers\StudiesRepositoryController;

    $TOS = (new StudiesRepositoryController())->TypeOfStudies() ?? array();
    $FieldNames = (new SearchFieldsRepositoryController())->SearchFields() ?? array();
    $globaloldpicodata = $PreviousData['PICOData'] ?? array();
    $oldTOS = $PreviousData['TOS'] ?? array();
    $cachetmp = $PreviousData['cachetmp'] ?? '';
    $TmpCookieElement = $PreviousData['TmpCookieElement'] ?? '';
    ?>

    <?php for($PICOiterative=1;$PICOiterative<6;$PICOiterative++): ?>

        <?php
            $olddata = $globaloldpicodata[$PICOiterative] ?? null;
        ?>
        <?php if($PICOiterative<5): ?>
            <?php echo $__env->make('layout.PICOelementlayout',['PICOiterative' => $PICOiterative,'FieldsNames'=>$FieldNames,'olddata'=>$olddata], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php else: ?>
            <?php echo $__env->make('layout.PICOelementlayout',['PICOiterative' => $PICOiterative,'TOS'=>$TOS,'olddata'=>$olddata,'oldTOS' => $oldTOS, 'cachetmp' => $cachetmp, 'TmpCookieElement' => $TmpCookieElement], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php endif; ?>
    <?php endfor; ?>

</div>
<?php /**PATH C:\xampp\htdocs\Laravel-BIREME\resources\views/partials/PICO.blade.php ENDPATH**/ ?>