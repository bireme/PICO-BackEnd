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
    private $ErrorCode;

    public function __construct($resultsData, $EqNum, $PICOname) {
        $this->FacadeResultsNumberTimer = new \SimpleLife\Timer();
        $this->SimpleLifeMessage = new SimpleLifeMessage('FacadeResultsNumberObtainer');
        $this->SimpleLifeMessage->AddAsNewLine('$PICO=' . $PICOname . ' $ResultsQuery = ' . json_encode($resultsData));
        $this->ObjectResultList = new ObjectResultList($EqNum, $resultsData, $PICOname);
        $this->ResultsNumberProcessor = new ControllerResultsNumberHandler($this->ObjectResultList);
    }

    public function BuildResultsNumber() {
        $fun = $this->ResultsNumberProcess();
        if ($fun) {
            return $this->setErrorCode($fun);
        }
    }

    private function ResultsNumberProcess() {
        $fun = $this->ResultsNumberProcessor->BuildResultsNumber();
        if ($fun) {
            return $fun;
        }
    }

    public function getResultsNumber() {
        $res = $this->ObjectResultList->getResults();
        $this->OperationReport($res);
        if ($this->ErrorCode) {
            $result= json_encode(array('Error' => $this->ShowErrorCodeToUser()));
        } else {
            $result= json_encode(array('Data' => $res));
        }
        $this->OperationReport($result);
        return $result;
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

    private function setErrorCode($ErrorCode) {
        $this->ErrorCode = $ErrorCode;
    }

    private function ShowErrorCodeToUser() {
        return '[Error ' . $this->ErrorCode . ']Este error se traducirá y se mostrará si es relevante para el usuario';
    }

}

?>