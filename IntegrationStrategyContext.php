<?php

require_once(realpath(dirname(__FILE__)) . '/LayerIntegration/ControllerImportDeCS.php');
require_once(realpath(dirname(__FILE__)) . '/LayerIntegration/ControllerImportResultsNumber.php');
require_once(realpath(dirname(__FILE__)) . '/SimpleLife/ManualExceptions.php');
require_once(realpath(dirname(__FILE__)) . '/SimpleLife/SimpleLifeMessages.php');
require_once(realpath(dirname(__FILE__)) . '/SimpleLife/Timer.php');

use SimpleLife\SimpleLifeMessage;
use LayerIntegration\ControllerImportDeCS;
use LayerIntegration\ControllerImportResultsNumber;
use SimpleLife\TimeSum;
use SimpleLife\Timer;

class StrategyContext {

    private $strategy = NULL;
    private $ConnectionTimer;
    private $res;

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
        $this->ConnectionTimer = new TimeSum;
        $this->IntegrationTimer = new Timer();
        $params['timeSum'] = $this->ConnectionTimer;
        $msg = new SimpleLifeMessage('Integration Call');
        $msg->AddAsNewLine($params['info']);
        $msg->AddEmptyLine();
        $ErrorCode = $this->strategy->obtainInfo($params) ?: false;
        $ConnectionTime = $this->ConnectionTimer->Stop();
        $IntegrationTime = $this->IntegrationTimer->Stop();
        if ($ErrorCode != false) {
            $msg->AddAsNewLine('Error Code: ' . $ErrorCode);
            $msg->SendAsError();
            return $ErrorCode;
        } else {
            $msg->AddAsNewLine('Total time elapsed  = ' . $IntegrationTime . ' ms');
            $msg->AddAsNewLine('Connection/Proxy time  = ' . $ConnectionTime . ' ms');
            $msg->AddAsNewLine('Operational time  = ' . ($IntegrationTime - $ConnectionTime) . ' ms');
            $msg->SendAsLog();
        }
    }

}

?>