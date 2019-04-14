<?php

// Check if the form is submitted
require_once(realpath(dirname(__FILE__)) . '/LayerBusiness/ControllerResultsNumberHandler.php');

use LayerBusiness\ControllerResultsNumberHandler;

if (filter_has_var(INPUT_GET, 'submit')) {

    $query = $_GET['query'];
    $obj = new ControllerResultsNumberHandler($query);
    exit;
}
?>