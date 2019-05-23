<?php

require_once(realpath(dirname(__FILE__)) . '/SimpleLife/SimpleLifeMessages.php');
require_once(realpath(dirname(__FILE__)) . '/SimpleLife/ManualExceptions.php');
require_once(realpath(dirname(__FILE__)) . '/LayerBusiness/ControllerQueryBuilder.php');
require_once(realpath(dirname(__FILE__)) . '/LayerBusiness/ControllerDeCSQueryProcessor.php');
require_once(realpath(dirname(__FILE__)) . '/LayerBusiness/ControllerQueryBuilder.php');
require_once(realpath(dirname(__FILE__)) . '/LayerBusiness/ControllerEquationChecker.php');
require_once(realpath(dirname(__FILE__)) . '/LayerEntities/ObjectQuery.php');
require_once(realpath(dirname(__FILE__)) . '/SimpleLife/Timer.php');

use LayerBusiness\ControllerEquationChecker;
use LayerBusiness\ControllerDeCSQueryProcessor;
use LayerBusiness\ControllerQueryBuilder;
use SimpleLife\Timer;
use LayerEntities\ObjectQuery;
use SimpleLife\SimpleLifeException;
use SimpleLife\SimpleLifeMessage;

class FacadeQueryBuilder {

    private $queryBuilder;
    private $ObjectQuery;
    private $SimpleLifeMessage;
    private $FacadeQueryBuilderTimer;
    private $ErrorCode;

    public function BuildnewQuery() {
        $fun = $this->queryBuilder->BuildnewQuery();
        if ($fun) {
            return $fun;
        }
        $fun = $this->FixEquation();
        if ($fun) {
            return $fun;
        }
    }

    private function FixEquation() {
        $EquationProcessor = new ControllerEquationChecker(NULL);
        $this->ObjectQuery->setQuery($EquationProcessor->FixEquation($this->ObjectQuery->getQuery()));
    }

    public function __construct($results, $SelectedDescriptors, $ImproveSearch, $EqName) {
        $DeCSqueryProcessor = new ControllerDeCSQueryProcessor($ImproveSearch, NULL);
        $ImproveSearchArr = $DeCSqueryProcessor->splitQuery($ImproveSearch);
        $this->ObjectQuery = new ObjectQuery($results, $SelectedDescriptors, $ImproveSearchArr, $EqName);
        $this->queryBuilder = new ControllerQueryBuilder($this->ObjectQuery);
        $this->FacadeQueryBuilderTimer = new \SimpleLife\Timer();
        $this->SimpleLifeMessage = new SimpleLifeMessage('FacadeQueryBuilder');
        $this->SimpleLifeMessage->AddAsNewLine('results=' . $results . ' SelectedDescriptors=' . json_encode($SelectedDescriptors) . ' ImproveSearch = "' . $ImproveSearch . '"');
    }

    public function getResults() {
        if ($this->ErrorCode) {
            $result = array('Error' => $this->ShowErrorCodeToUser());
        } else {
            $result = json_encode(array('Data' => $this->ObjectQuery->getResults()));
        }
        $this->OperationReport($result);
        return $result;
    }

    private function OperationReport($result) {
        $time_elapsed = $this->FacadeQueryBuilderTimer->Stop();
        $this->SimpleLifeMessage->AddAsNewLine('Total Time: ' . $time_elapsed . ' ms');
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