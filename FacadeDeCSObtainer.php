<?php

require_once(realpath(dirname(__FILE__)) . '/SimpleLife/SimpleLifeMessages.php');
require_once(realpath(dirname(__FILE__)) . '/SimpleLife/ManualExceptions.php');
require_once(realpath(dirname(__FILE__)) . '/LayerBusiness/ControllerDeCSQueryProcessor.php');
require_once(realpath(dirname(__FILE__)) . '/LayerBusiness/ControllerDeCSLooper.php');
require_once(realpath(dirname(__FILE__)) . '/LayerBusiness/ControllerEquationChecker.php');
require_once(realpath(dirname(__FILE__)) . '/LayerBusiness/ControllerDeCSMenuBuilder.php');
require_once(realpath(dirname(__FILE__)) . '/LayerEntities/ObjectKeywordList.php');
require_once(realpath(dirname(__FILE__)) . '/LayerEntities/ObjectKeyword.php');
require_once(realpath(dirname(__FILE__)) . '/LayerEntities/ObjectEquation.php');
require_once(realpath(dirname(__FILE__)) . '/SimpleLife/Timer.php');
require_once(realpath(dirname(__FILE__)) . '/LayerEntities/DeCSMenu.php');

use LayerEntities\DeCSMenu;
use SimpleLife\Timer;
use LayerEntities\ObjectKeywordList;
use LayerEntities\ObjectKeyword;
use LayerBusiness\ControllerEquationChecker;
use LayerBusiness\ControllerDeCSQueryProcessor;
use LayerBusiness\ControllerDeCSLooper;
use LayerBusiness\ControllerDeCSMenuBuilder;
use SimpleLife\SimpleLifeException;
use SimpleLife\SimpleLifeMessage;

class FacadeDeCSObtainer {

    private $queryProcesser;
    private $DeCSProcessor;
    private $ObjectKeywordList;
    private $SimpleLifeMessage;
    private $FacadeDeCSTimer;
    private $DeCSMenuObject;
    private $DeCSMenuBuilder;
    private $PICOnum;
    private $ErrorCode;
    private $EqName;

    public function __construct($query, $langs, $PICOnum, $EqName) {
        $this->PICOnum = $PICOnum;
        $this->EqName=$EqName;
        $this->ObjectKeywordList = new ObjectKeywordList($langs,$query);
        $this->EquationProcessor = new ControllerEquationChecker($this->ObjectKeywordList);
        $this->queryProcesser = new ControllerDeCSQueryProcessor($this->ObjectKeywordList);
        $this->DeCSProcessor = new ControllerDeCSLooper($this->ObjectKeywordList);
        $this->DeCSMenuBuilder = new ControllerDeCSMenuBuilder($this->ObjectKeywordList);
        $this->FacadeDeCSTimer = new \SimpleLife\Timer();
        $this->SimpleLifeMessage = new SimpleLifeMessage('FacadeDeCSObtainer');
        $this->SimpleLifeMessage->AddAsNewLine('Langs=' . json_encode($langs) . ' query = "' . $query . '"');
    }

    public function buildDeCSMenu() {
        $this->DeCSMenuBuilder->BuildHTML($this->PICOnum);
    }

    public function getKeywordDeCS() {
        $fun = $this->CheckEquation();
        if ($fun) {
            return $this->setErrorCode($fun);
        }
        $fun = $this->getKeywordList();
        if ($fun) {
            return $this->setErrorCode($fun);
        }
        $fun = $this->BuildDeCSList();
        if ($fun) {
            return $this->setErrorCode($fun);
        }
        $fun = $this->buildDeCSMenu();
        if ($fun) {
            return $this->setErrorCode($fun);
        }
    }

    private function CheckEquation() {
        return $this->EquationProcessor->CheckEquation($this->EqName);
    }

    private function OperationReport($result) {
        $time_elapsed = $this->FacadeDeCSTimer->Stop();
        $ConnectionTime = $this->ObjectKeywordList->getConnectionTimeSum();
        $this->SimpleLifeMessage->AddAsNewLine('Total Time: ' . $time_elapsed . ' ms');
        $this->SimpleLifeMessage->AddAsNewLine('Time Spent in connections: ' . $ConnectionTime . ' ms');
        $this->SimpleLifeMessage->AddAsNewLine('Operational time: ' . ($time_elapsed - $ConnectionTime) . ' ms');
        $this->SimpleLifeMessage->AddEmptyLine();
        $this->SimpleLifeMessage->AddAsNewLine('Info returned to user:');
        $this->SimpleLifeMessage->AddAsNewLine(json_encode(json_decode($result,true)['Data']['results']));
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

    public function getResults() {
        if ($this->ErrorCode) {
            $result = json_encode(array('Error' => $this->ShowErrorCodeToUser()));
        } else {
            $result = json_encode(array('Data' => $this->ObjectKeywordList->getResults()));
        }
        $this->OperationReport($result);
        return $result;
    }

    private function setErrorCode($ErrorCode) {
        $this->ErrorCode = $ErrorCode;
    }

    private function ShowErrorCodeToUser() {
        return '[Error ' . $this->ErrorCode . ']Este error se traducirá y se mostrará si es relevante para el usuario';
    }

}

?>