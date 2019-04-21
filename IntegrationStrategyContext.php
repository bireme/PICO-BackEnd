<?php

require_once(realpath(dirname(__FILE__)) . '/LayerIntegration/ControllerImportDeCS.php');
require_once(realpath(dirname(__FILE__)) . '/LayerIntegration/ControllerImportResultsNumber.php');
require_once(realpath(dirname(__FILE__)) . '/LayerIntegration/IntegrationExceptions.php');

use LayerIntegration\ControllerImportDeCS;
use LayerIntegration\ControllerImportResultsNumber;
use LayerIntegration\IntegrationExceptions;

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
        try {
            $ErrorCode = "--";
            $start = microtime(true);
            $this->strategy->startTimer();
            $res = $this->strategy->obtainInfo($params);
            return $res;
        }catch (IntegrationExceptions $exc) {
            if ($exc->HandleError()) {
                $exc->HandleError();
                $ErrorCode = $exc->PreviousUserErrorCode();
                //throw new IntegrationExceptions($Ex->build(), $exc->PreviousUserErrorCode());
            }



            //return NULL;
        } finally {
            $timer = $this->strategy->getTimer();
            $time_elapsed_secs = (int) ((microtime(true) - $start) * 1000);
            echo '</br></br>-------------------------------------------------------';
            echo '</br>Time Elapsed Report';
            echo '</br>------------------------------------------------------------';
            echo '</br></br> Total time elapsed  = ' . $time_elapsed_secs . ' ms';
            echo '</br>--- Connection/Proxy time  = ' . $timer . ' ms';
            echo '</br>---Operational time  = ' . ($time_elapsed_secs - $timer) . ' ms</br>';
            echo '</br>------------------------------------------------------------';
            echo '</br>Errors received: [' . $ErrorCode . ']';
            echo '</br>------------------------------------------------------------';
        }
    }

}

?>