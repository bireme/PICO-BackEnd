<?php

// Check if the form is submitted
require_once(realpath(dirname(__FILE__)) . '/LayerBusiness/ControllerDeCSHandler.php');

use LayerBusiness\ControllerDeCSHandler;

if (filter_has_var(INPUT_GET, 'submit')) {

    $keyword = $_GET['keyword'];
    $arrayPosition = $_GET['position'];
    $langs = $_GET['languages'];


    $obj = new ControllerDeCSHandler($keyword, $arrayPosition, $langs);
    exit;
}
?>