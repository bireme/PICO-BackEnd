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
require_once(realpath(dirname(__FILE__)) . '/LayerEntities/ObjectQuerySplitList.php');

use LayerEntities\ObjectQuerySplitList;
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
    private $ObjectQuerySplitList;
    private $SimpleLifeMessage;
    private $FacadeDeCSTimer;
    private $DeCSMenuObject;
    private $DeCSMenuBuilder;
    private $PICOnum;
    private $ErrorCode;
    private $EqName;

    private function setMaxImports() {
        return array('MaxRelatedTrees' => 5,
            'MaxDescendantExplores' => 5);
    }

    public function __construct($query, $langs, $PICOnum, $EqName, $results, $mainLanguage) {
        $this->PICOnum = $PICOnum;
        $this->EqName = $EqName;
        $ObjectKeywordList = new ObjectKeywordList($langs,$this->setMaxImports());
        $this->ObjectQuerySplitList = new ObjectQuerySplitList($query, $ObjectKeywordList, $langs, $mainLanguage);
        $this->EquationProcessor = new ControllerEquationChecker($this->ObjectQuerySplitList);
        $this->queryProcesser = new ControllerDeCSQueryProcessor($this->ObjectQuerySplitList, $ObjectKeywordList, $results);
        $this->DeCSProcessor = new ControllerDeCSLooper($ObjectKeywordList);
        $this->DeCSMenuBuilder = new ControllerDeCSMenuBuilder($this->ObjectQuerySplitList);
        $this->FacadeDeCSTimer = new \SimpleLife\Timer();
        $this->SimpleLifeMessage = new SimpleLifeMessage('FacadeDeCSObtainer');
        $this->SimpleLifeMessage->AddAsNewLine('Langs=' . json_encode($langs) . ' query = "' . $query . '"');
    }

    public function buildDeCSMenu() {
        return $this->DeCSMenuBuilder->BuildHTML($this->PICOnum);
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
        $ConnectionTime = $this->ObjectQuerySplitList->getObjectKeywordList()->getConnectionTimeSum();
        $this->SimpleLifeMessage->AddAsNewLine('Total Time: ' . $time_elapsed . ' ms');
        $this->SimpleLifeMessage->AddAsNewLine('Time Spent in connections: ' . $ConnectionTime . ' ms');
        $this->SimpleLifeMessage->AddAsNewLine('Operational time: ' . ($time_elapsed - $ConnectionTime) . ' ms');
        $this->SimpleLifeMessage->AddEmptyLine();
        $this->SimpleLifeMessage->AddAsNewLine('Info returned to user:');
        $result = json_decode($result, true);
        if (array_key_exists('Data', $result)) {
            $this->SimpleLifeMessage->AddAsNewLine(json_encode($result['Data']['results']));
        } else {
            $this->SimpleLifeMessage->AddAsNewLine(json_encode($result));
        }

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
            $result = json_encode(array('Data' => $this->ObjectQuerySplitList->getResults()));
        }
        $this->OperationReport($result);
        return $result;
    }

    private function setErrorCode($ErrorCode) {
        $this->ErrorCode = $ErrorCode;
    }

    private function ShowErrorCodeToUser() {
        if ($this->ErrorCode == 321) {
            return 'The equation did not change';
        }
        return '[Error ' . $this->ErrorCode . ']Este error se traducirá y se mostrará si es relevante para el usuario';
    }

}

?>