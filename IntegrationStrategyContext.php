<?php

/**
 * @access public
 * @author Daniel Nieto
 */
require_once(realpath(dirname(__FILE__)) . '/LayerIntegration\ControllerImportDeCS.php');
require_once(realpath(dirname(__FILE__)) . '/LayerIntegration\ControllerImportResultsNumber.php');


use LayerIntegration\ControllerImportDeCS;
use LayerIntegration\ControllerImportResultsNumber;

class StrategyContext {

    private $strategy = NULL;

    //bookList is not instantiated at construct time
    public function __construct($idx) {
        switch ($idx) {
            case "DeCS":
                $this->strategy = new ControllerImportDeCS();
                break;
            case "ResultsNumber":
                $this->strategy = new ControllerImportResultsNumber();
                break;
        }
    }

    public function obtainInfo($params) {
        $res = $this->strategy->obtainInfo($params);
        return $res;
    }

}


?>