<?php

require_once(realpath(dirname(__FILE__)) . '/SimpleLife/SimpleLifeMessages.php');
require_once(realpath(dirname(__FILE__)) . '/SimpleLife/ManualExceptions.php');
require_once(realpath(dirname(__FILE__)) . '/LayerBusiness/ControllerDeCSQueryProcessor.php');
require_once(realpath(dirname(__FILE__)) . '/LayerBusiness/ControllerDeCSLooper.php');
require_once(realpath(dirname(__FILE__)) . '/LayerBusiness/ControllerEquationChecker.php');
require_once(realpath(dirname(__FILE__)) . '/LayerEntities/ObjectKeywordList.php');
require_once(realpath(dirname(__FILE__)) . '/LayerEntities/ObjectEquation.php');
require_once(realpath(dirname(__FILE__)) . '/SimpleLife/Timer.php');

use SimpleLife\Timer;
use LayerEntities\ObjectKeywordList;
use LayerEntities\ObjectEquation;
use LayerBusiness\ControllerEquationChecker;
use LayerBusiness\ControllerDeCSQueryProcessor;
use LayerBusiness\ControllerDeCSLooper;
use SimpleLife\SimpleLifeException;
use SimpleLife\SimpleLifeMessage;

class FacadeDeCSObtainer {

    private $queryProcesser;
    private $DeCSProcessor;
    private $ObjectKeywordList;
    private $SimpleLifeMessage;
    private $FacadeDeCSTimer;

    public function __construct($query, $langs, $EqName) {
        $this->ObjectEquation = new ObjectEquation($query, $EqName);
        $this->EquationProcessor = new ControllerEquationChecker($this->ObjectEquation);
        $this->ObjectKeywordList = new ObjectKeywordList($langs);
        $this->queryProcesser = new ControllerDeCSQueryProcessor($query, $this->ObjectKeywordList);
        $this->DeCSProcessor = new ControllerDeCSLooper($this->ObjectKeywordList);
        $this->FacadeDeCSTimer = new \SimpleLife\Timer();
        $this->SimpleLifeMessage = new SimpleLifeMessage('FacadeDeCSObtainer');
        $this->SimpleLifeMessage->AddAsNewLine('Langs=' . json_encode($langs) . ' query = "' . $query . '"');
    }

    public function getKeywordDeCS() {

        $fun = $this->getKeywordList();
        if ($fun) {
            return $this->ShowErrorCodeToUser($fun);
        }
        $fun = $this->BuildDeCSList();
        if ($fun) {
            return $this->ShowErrorCodeToUser($fun);
        }
        $res = $this->getResults();
        $this->OperationReport($res);
        return $res;
    }

    private function CheckEquation() {
        $fun = $this->EquationProcessor->FixEquation();
        if ($fun) {
            return $fun;
        }
        $fun = $this->EquationProcessor->CheckEquation();
        if ($fun) {
            return $fun;
        }
        $Query = $this->ObjectEquation->getNewEQuation();
         $this->queryProcesser->setEquation($Query);
    }

    private function OperationReport($result) {
        $time_elapsed = $this->FacadeDeCSTimer->Stop();
        $ConnectionTime = $this->ObjectKeywordList->getConnectionTimeSum();
        $this->SimpleLifeMessage->AddAsNewLine('Total Time: ' . $time_elapsed . ' ms');
        $this->SimpleLifeMessage->AddAsNewLine('Time Spent in connections: ' . $ConnectionTime . ' ms');
        $this->SimpleLifeMessage->AddAsNewLine('Operational time: ' . ($time_elapsed - $ConnectionTime) . ' ms');
        $this->SimpleLifeMessage->AddEmptyLine();
        $this->SimpleLifeMessage->AddAsNewLine('Info returned to user:');
        $this->SimpleLifeMessage->AddAsNewLine(json_encode($result));
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