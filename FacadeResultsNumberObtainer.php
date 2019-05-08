<?php

require_once(realpath(dirname(__FILE__)) . '/SimpleLife/SimpleLifeMessages.php');
require_once(realpath(dirname(__FILE__)) . '/SimpleLife/ManualExceptions.php');
require_once(realpath(dirname(__FILE__)) . '/SimpleLife/Timer.php');
require_once(realpath(dirname(__FILE__)) . '/LayerBusiness/ControllerResultsNumberHandler.php');
require_once(realpath(dirname(__FILE__)) . '/LayerEntities/ObjectResultList.php');

use SimpleLife\Timer;
use LayerBusiness\ControllerResultsNumberHandler;
use LayerEntities\ObjectResultList;
use SimpleLife\SimpleLifeException;
use SimpleLife\SimpleLifeMessage;

class FacadeResultsNumberObtainer {

    private $SimpleLifeMessage;
    private $FacadeResultsNumberTimer;
    private $ObjectResultList;

    public function __construct($resultsData, $EqNum,$PICOname) {
        $this->FacadeResultsNumberTimer = new \SimpleLife\Timer();
        $this->SimpleLifeMessage = new SimpleLifeMessage('FacadeResultsNumberObtainer');
        $this->SimpleLifeMessage->AddAsNewLine('$PICO=' . $PICOname . ' $ResultsQuery = ' . json_encode($resultsData));
        $this->ObjectResultList = new ObjectResultList($EqNum,$resultsData,$PICOname);
        $this->ResultsNumberProcessor = new ControllerResultsNumberHandler($this->ObjectResultList);
    }

    public function getResultsNumber() {
        $fun = $this->BuildResultsNumber();
        if ($fun) {
            return $this->ShowErrorCodeToUser($fun);
        }
        $res = ($this->getResults());
        $this->OperationReport($res);
        return $res;
    }

    private function BuildResultsNumber() {
        $fun = $this->ResultsNumberProcessor->BuildResultsNumber();
        if ($fun) {
            return $fun;
        }
    }

    private function getResults() {
        return $this->ObjectResultList->getResults();
    }

    private function OperationReport($result) {
        $time_elapsed = $this->FacadeResultsNumberTimer->Stop();
        $ConnectionTime = $this->ObjectResultList->getConnectionTimeSum();
        $this->SimpleLifeMessage->AddAsNewLine('Total Time: ' . $time_elapsed . ' ms');
        $this->SimpleLifeMessage->AddAsNewLine('Time Spent in connections: ' . $ConnectionTime . ' ms');
        $this->SimpleLifeMessage->AddAsNewLine('Operational time: ' . ($time_elapsed - $ConnectionTime) . ' ms');
        $this->SimpleLifeMessage->AddEmptyLine();
        $this->SimpleLifeMessage->AddAsNewLine('Info returned to user:');
        $this->SimpleLifeMessage->AddAsNewLine(json_encode($result));
        $this->SimpleLifeMessage->SendAsLog();
    }
    
    private function ShowErrorCodeToUser($ErrorCode) {
        return '[Error ' . $ErrorCode . ']Este error se traducirá y se mostrará si es relevante para el usuario';
    }

}

?>