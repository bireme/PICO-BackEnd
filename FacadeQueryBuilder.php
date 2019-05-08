<?php

require_once(realpath(dirname(__FILE__)) . '/SimpleLife/SimpleLifeMessages.php');
require_once(realpath(dirname(__FILE__)) . '/SimpleLife/ManualExceptions.php');
require_once(realpath(dirname(__FILE__)) . '/LayerBusiness/ControllerQueryBuilder.php');
require_once(realpath(dirname(__FILE__)) . '/LayerEntities/ObjectQuery.php');
require_once(realpath(dirname(__FILE__)) . '/SimpleLife/Timer.php');

use SimpleLife\Timer;
use LayerEntities\ObjectQuery;
use SimpleLife\SimpleLifeException;
use SimpleLife\SimpleLifeMessage;

class FacadeQueryBuilder {

    private $queryBuilder;
    private $ObjectQuery;
    private $SimpleLifeMessage;
    private $FacadeQueryBuilderTimer;
    private $EquationProcessor;

    public function getNewQuery() {
        $fun = $this->CheckEquation();
        if ($fun) {
            return $this->ShowErrorCodeToUser($fun);
        }
        $fun = $this->BuildnewQuery();
        if ($fun) {
            return $this->ShowErrorCodeToUser($fun);
        }
        $res = $this->getResults();
        $this->OperationReport($res);
        return $res;
    }

    private function BuildnewQuery() {
        $fun = $this->queryBuilder->BuildnewQuery();
        if ($fun) {
            return $fun;
        }
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
        $this->ObjectQuery->setEquation($Query);
    }

    public function __construct($query, $SelectedDescriptors, $ImproveSearch, $EqName) {
        $this->ObjectEquation = new ObjectEquation($query, $EqName);
        $this->EquationProcessor = new ControllerEquationCheker($this->ObjectEquation);
        $this->ObjectQuery = new ObjectQuery($query, $SelectedDescriptors, $ImproveSearch);
        $this->queryBuilder = new ControllerQueryBuilder($this->ObjectQuery);
        $this->FacadeQueryBuilderTimer = new \SimpleLife\Timer();
        $this->SimpleLifeMessage = new SimpleLifeMessage('FacadeQueryBuilder');
        $this->SimpleLifeMessage->AddAsNewLine('query=' . $query . ' SelectedDescriptors=' . json_encode($SelectedDescriptors) . ' ImproveSearch = "' . $ImproveSearch . '"');
    }

    private function getResults() {
        return $this->ObjectQuery->getResults();
    }

    private function OperationReport($result) {
        $time_elapsed = $this->FacadeQueryBuilderTimer->Stop();
        $ConnectionTime = $this->ObjectKeywordList->getConnectionTimeSum();
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