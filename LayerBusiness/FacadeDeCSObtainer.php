<?php

namespace LayerBusiness;

require_once(realpath(dirname(__FILE__)) . '/../SimpleLife/SimpleLifeMessages.php');
require_once(realpath(dirname(__FILE__)) . '/../SimpleLife/ManualExceptions.php');
require_once(realpath(dirname(__FILE__)) . '/../LayerBusiness/ControllerDeCSQueryProcessor.php');
require_once(realpath(dirname(__FILE__)) . '/../LayerBusiness/ControllerDeCSLooper.php');
require_once(realpath(dirname(__FILE__)) . '/../LayerEntities/ObjectKeywordList.php');
require_once(realpath(dirname(__FILE__)) . '/../SimpleLife/Timer.php');

use SimpleLife\Timer;
use LayerEntities\ObjectKeywordList;
use SimpleLife\SimpleLifeException;
use SimpleLife\SimpleLifeMessage;

class FacadeDeCSObtainer {

    private $queryProcesser;
    private $DeCSProcessor;
    private $ObjectKeywordList;
    private $SimpleLifeMessage;
    private $FacadeDeCSTimer;

    public function __construct($query, $langs) {
        $this->ObjectKeywordList = new ObjectKeywordList($langs);
        $this->queryProcesser = new ControllerDeCSQueryProcessor($query, $this->ObjectKeywordList);
        $this->DeCSProcessor = new ControllerDeCSLooper($this->ObjectKeywordList);
        $this->FacadeDeCSTimer = new \SimpleLife\Timer();
        $this->SimpleLifeMessage = new SimpleLifeMessage('FacadeDeCSObtainer');
        $this->SimpleLifeMessage->AddAsNewLine('Langs=' . json_encode($langs) . ' query = "' . $query . '"');
    }

    public function getKeywordDeCSJSON() {

        $fun=$this->getKeywordList();
        if($fun){
            return $this->ShowErrorCodeToUser($fun);
        }
        $fun=$this->BuildDeCSList();
        if($fun){
            return $this->ShowErrorCodeToUser($fun);
        }
        $res = json_encode($this->getResults());
        $this->OperationReport($res);
        return $res;
    }

    private function OperationReport($result) {
        $time_elapsed = $this->FacadeDeCSTimer->Stop();
        $ConnectionTime = $this->ObjectKeywordList->getConnectionTimeSum();
        $this->SimpleLifeMessage->AddAsNewLine('Total Time: ' . $time_elapsed . ' ms');
        $this->SimpleLifeMessage->AddAsNewLine('Time Spent in connections: ' . $ConnectionTime . ' ms');
        $this->SimpleLifeMessage->AddAsNewLine('Operational time: ' . ($time_elapsed - $ConnectionTime) . ' ms');
        $this->SimpleLifeMessage->AddEmptyLine();
        $this->SimpleLifeMessage->AddAsNewLine('Info returned to user:');
        $this->SimpleLifeMessage->AddAsNewLine($result);
        $this->SimpleLifeMessage->SendAsLog();
    }

    private function getKeywordList() {
        $this->queryProcesser->BuildKeywordList();
    }

    private function BuildDeCSList() {
        $fun = $this->DeCSProcessor->BuildDeCSList();
        if ($fun) {
            return $fun;
        }
    }

    private function getResults() {
        return $this->ObjectKeywordList->getFullDeCSList();
    }

    private function ShowErrorCodeToUser($ErrorCode) {
        return '[Error ' . $ErrorCode . ']Este error se traducirá y se mostrará si es relevante para el usuario';
    }

}

?>