<?php

require_once(realpath(dirname(__FILE__)) . '/LayerBusiness/FacadeDeCSObtainer.php');

use LayerBusiness\FacadeDeCSObtainer;

if (filter_has_var(INPUT_GET, 'submit')) {
    $langArr = array();
    $Equation = $_GET['Equation'];

    if (!empty($_GET['Languages'])) {
// Loop to store and display values of individual checked checkbox.
        foreach ($_GET['Languages'] as $selected) {
            array_push($langArr, $selected);
        }
    }
    $obj = new FacadeDeCSObtainer($Equation, $langArr);
    $result = $obj->getKeywordDeCSJSON();
    echo '<font color="blue"></br></br></br></br>---Información recibida por usuario------------------------------------------</br>';
    echo $result . '</font>';
    exit;
}
?>