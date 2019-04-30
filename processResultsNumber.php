<?php

require_once(realpath(dirname(__FILE__)) . '/LayerBusiness/FacadeResultsNumberObtainer.php');
require_once(realpath(dirname(__FILE__)) . '/LayerEntities/ObjectResultList.php');

use LayerBusiness\FacadeResultsNumberObtainer;

if (filter_has_var(INPUT_GET, 'submit')) {
    $PICOsElement = 'Búsqueda de prueba';
    $Query = $_GET['query'];
    $SearchField = 'All fields';
    $QueryList = array(array('Query' => $Query, 'Title' => $PICOsElement));

    $obj = new FacadeResultsNumberObtainer($PICOsElement, $SearchField, $QueryList);
    $result = $obj->getResultsNumberJSON();

    echo '<font color="blue"></br></br></br></br>---Información que será recibida por el usuario------------------------------------------</br>';
    echo $result . '</font>';
}
?>