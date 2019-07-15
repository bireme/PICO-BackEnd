<meta charset="UTF-8">
<meta name="autor" content="<?php echo e(__('lang.autor')); ?>">
<meta name="keywords" content="<?php echo e(__('lang.keywords')); ?>">
<meta name="description" content="<?php echo e(__('lang.description')); ?>">
<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo e(__('lang.title')); ?></title>
<?php
    $currentlang = Config::get('app.locale');
    $langs = Config::get('languages');
    $baseURL = url('/');
?>
<?php $__currentLoopData = $langs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lang => $language): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <?php if($currentlang==$lang): ?>
        <?php continue; ?>
    <?php endif; ?>
    <link rel="alternate" hreflang="<?php echo e($lang); ?>" href="<?php echo e($baseURL . '/' . $lang); ?>" />
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<link rel="stylesheet" href="<?php echo e(mix('css/all.css')); ?>">

<?php /**PATH C:\xampp\htdocs\Laravel-BIREME\resources\views/partials/head.blade.php ENDPATH**/ ?>