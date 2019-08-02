<?php $env = isset($env) ? $env : null; ?>
<?php $environment = isset($environment) ? $environment : null; ?>
<?php $now = isset($now) ? $now : null; ?>
<?php

<?php
    $now = new DateTime();

    $environment = isset($env) ? $env : "testing";
?>

<?php $__container->startTask('composer'); ?>
composer install
<?php $__container->endTask(); ?>
