<header id="header">
    <div id="lang">
        <?php if( Config::get('app.locale') != 'en'): ?>
        <a href="<?php echo e(url('locale/en')); ?>" class="langbut"id="page-lang0" >English</a><label id="sep-lang0"> | </label>
        <?php endif; ?>
        <?php if( Config::get('app.locale') != 'pt'): ?>
        <a href="<?php echo e(url('locale/pt')); ?>" class="langbut" id="page-lang1">Português</a><label id="sep-lang1"> | </label>
        <?php endif; ?>
        <?php if( Config::get('app.locale') != 'es'): ?>    
        <a href="<?php echo e(url('locale/es')); ?>" class="langbut"id="page-lang2">Español</a>
        <?php endif; ?>
        <?php if(( Config::get('app.locale') != 'es') && ( Config::get('app.locale') != 'fr')): ?>
        <label id="sep-lang2"> | </label>
        <?php endif; ?>
        <?php if( Config::get('app.locale') != 'fr'): ?>    
        <a href="<?php echo e(url('locale/fr')); ?>" class="langbut" id="page-lang3">Français</a>
        <?php endif; ?>
        <label id="sep-lang3"> </label>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-md-4" id="logo">
                <a href=""><img src="img/BVS.svg" alt="" class="img-fluid"></a>
            </div>
            <div class="col-md-8" id="pico">
                <img src="img/Picos.svg" alt="" class="img-fluid">
            </div>
        </div>
    </div>
</header><?php /**PATH C:\xampp\htdocs\Laravel-BIREME\resources\views/layout/partials/header.blade.php ENDPATH**/ ?>