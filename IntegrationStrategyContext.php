<?php

/**
 * @access public
 * @author Daniel Nieto
 */
require_once(realpath(dirname(__FILE__)) . '/LayerIntegration\ControllerImportDeCSTitles.php');
require_once(realpath(dirname(__FILE__)) . '/LayerIntegration\ControllerImportDeCS.php');
require_once(realpath(dirname(__FILE__)) . '/LayerIntegration\ControllerImportResultsNumber.php');

use LayerIntegration\ControllerImportDeCSTitles;
use LayerIntegration\ControllerImportDeCS;
use LayerIntegration\ControllerImportResultsNumber;

class StrategyContext {

    private $strategy = NULL;

    //bookList is not instantiated at construct time
    public function __construct($idx) {
        switch ($idx) {
            case "DeCSTitles":
                $this->strategy = new ControllerImportDeCSTitles();
                break;
            case "DeCS":
                $this->strategy = new ControllerImportDeCS();
                break;
            case "ResultsNumber":
                $this->strategy = new ControllerImportResultsNumber();
                break;
        }
    }

    public function showResults($params) {
        echo 'params=' . json_encode($params);
        $result = $this->strategy->obtainInfo($params);
        echo '</br>---------------------------------------------------------------</br>';
        return $result;
    }

}

?>