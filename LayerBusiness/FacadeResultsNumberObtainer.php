<?php

namespace LayerBusiness;

require_once(realpath(dirname(__FILE__)) . '/../SimpleLife/SimpleLifeMessages.php');
require_once(realpath(dirname(__FILE__)) . '/../SimpleLife/ManualExceptions.php');
require_once(realpath(dirname(__FILE__)) . '/../SimpleLife/Timer.php');
require_once(realpath(dirname(__FILE__)) . '/../LayerBusiness/ControllerResultsNumberHandler.php');

use SimpleLife\Timer;
use LayerEntities\ObjectResultList;
use SimpleLife\SimpleLifeException;
use SimpleLife\SimpleLifeMessage;

class FacadeResultsNumberObtainer {

    private $SimpleLifeMessage;
    private $FacadeResultsNumberTimer;

    public function __construct($PICOsElement,$SearchField,$QueryList) {
        $this->FacadeResultsNumberTimer = new \SimpleLife\Timer();
        $this->SimpleLifeMessage = new SimpleLifeMessage('FacadeResultsNumberObtainer');
        $this->SimpleLifeMessage->AddAsNewLine('$PICOsElement=' . $PICOsElement . ' $SearchField = "' . $SearchField . '"');
        $this->ObjectResultList = new ObjectResultList($PICOsElement, $SearchField);
        $this->ObjectResultList->AddObjectResults($QueryList);
        $this->ResultsNumberProcessor = new ControllerResultsNumberHandler($this->ObjectResultList);
    }

    public function getResultsNumberJSON() {
        $fun = $this->getResultsNumber();
        if ($fun) {
            return $this->ShowErrorCodeToUser($fun);
        }
        $res = json_encode($this->getResults());
        $this->OperationReport($res);
        return $res;
    }

    private function getResultsNumber() {
        $fun = $this->ResultsNumberProcessor->getResultsNumber();
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
        $this->SimpleLifeMessage->AddAsNewLine($result);
        $this->SimpleLifeMessage->SendAsLog();
    }

    private function ShowErrorCodeToUser($ErrorCode) {
        return '[Error ' . $ErrorCode . ']Este error se traducirá y se mostrará si es relevante para el usuario';
    }

}

?>