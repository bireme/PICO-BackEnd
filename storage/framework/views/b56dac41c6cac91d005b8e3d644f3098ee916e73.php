<header id="header">
    <div id="lang">
        <?php
            $currentlang = Config::get('app.locale');
            $langs = Config::get('languages');
            $lastlang = array_keys($langs)[count($langs)-1];
        ?>

        <?php $__currentLoopData = $langs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lang => $language): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php if($currentlang==$lang): ?>
                <?php continue; ?>
            <?php endif; ?>
            <button class="langbut" id="page-lang<?php echo e($loop->index); ?>" name="<?php echo e($lang); ?>">
                <?php echo e($language); ?>

            </button>
            <?php if($loop->index<(count($langs)-1)): ?>
                <?php if(!($currentlang===$lastlang && $loop->index===(count($langs)-2))): ?>
                    <label> | </label>
                <?php endif; ?>
            <?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <label> </label>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-md-2 offset-2" id="logo">
                <h2>
                    <a href="<?php echo e(Request::url()); ?>">
                        <img src="img/BVS.svg" alt="<?php echo e(__('lang.description')); ?>" class="img-fluid">
                    </a>
                </h2>
            </div>
            <div class="col-md-6" id="pico">
                <h1>
                    <a href="<?php echo e(Request::url()); ?>">
                        <img src="img/Picos.svg" alt="<?php echo e(__('lang.title')); ?>" class="img-fluid">
                    </a>
                </h1>
            </div>
        </div>
    </div>
</header>
<?php /**PATH C:\xampp\htdocs\Laravel-BIREME\resources\views/partials/header.blade.php ENDPATH**/ ?>