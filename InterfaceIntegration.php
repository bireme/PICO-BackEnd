<?php

require_once(realpath(dirname(__FILE__)) . '/LayerIntegration/ControllerImportDeCS.php');
require_once(realpath(dirname(__FILE__)) . '/LayerIntegration/ControllerImportResultsNumber.php');
require_once(realpath(dirname(__FILE__)) . '/SimpleLife/ManualExceptions.php');
require_once(realpath(dirname(__FILE__)) . '/SimpleLife/SimpleLifeMessages.php');
require_once(realpath(dirname(__FILE__)) . '/SimpleLife/Timer.php');
require_once(realpath(dirname(__FILE__)) . '/LayerEntities/ObjectKeywordIntegration.php');
require_once(realpath(dirname(__FILE__)) . '/LayerEntities/ObjectResult.php');
require_once(realpath(dirname(__FILE__)) . '/LayerEntities/ResultQuery.php');

use LayerEntities\ObjectResult;
use LayerEntities\ResultQuery;
use LayerEntities\ObjectKeywordIntegration;
use SimpleLife\SimpleLifeMessage;
use LayerIntegration\ControllerImportDeCS;
use LayerIntegration\ControllerImportResultsNumber;
use SimpleLife\TimeSum;
use SimpleLife\Timer;

class InterfaceIntegration {

    private $ConnectionTimer;
    private $IntegrationTimer;
    private $Description;
    private $SimpleLifeMessage;
    private $Data;
    private $Result;

    public function __construct($Description) {
        $this->ConnectionTimer = new TimeSum;
        $this->IntegrationTimer = new Timer();
        $this->Description = $Description;
        $this->Result = NULL;
        $this->SimpleLifeMessage = new SimpleLifeMessage('Integration Report - ' . $Description);
        $this->Data = NULL;
    }

    private function IntegrationResults($fun) {
        if ($fun) {
            $obj = array('Error' => $this->ErrorHandler($fun));
        } else {
            $obj = array('Data' => $this->Result);
        }
        $this->IntegrationReport();
        return json_encode($obj);
    }

    private function IntegrationReport() {
        $ConnectionTime = $this->ConnectionTimer->Stop();
        $IntegrationTime = $this->IntegrationTimer->Stop();
        $this->SimpleLifeMessage->AddAsNewLine('Total time elapsed  = ' . $IntegrationTime . ' ms');
        $this->SimpleLifeMessage->AddAsNewLine('Connection/Proxy time  = ' . $ConnectionTime . ' ms');
        $this->SimpleLifeMessage->AddAsNewLine('Operational time  = ' . ($IntegrationTime - $ConnectionTime) . ' ms');
        $this->SimpleLifeMessage->SendAsLog();
    }

    private function ErrorHandler($fun) {
        return $fun;
    }

    public function ImportDeCS($Data) {
        $fun = $this->ImportDeCSDataIntegrity($Data) ||
                $this->ImportDeCSInner();
        return $this->IntegrationResults($fun);
    }

    public function ImportResultsNumber($Data) {
        $fun = $this->ImportResultsNumberDataIntegrity($Data) ||
                $this->ImportResultsNumberInner();
        return $this->IntegrationResults($fun);
    }

    ///////////////////////////////////////////////////
    //IMPORT DECS FUNCTION
    //////////////////////////////////////////////////

    private function ImportDeCSDataIntegrity($Data) {
        $Data = json_decode($Data, true);
        $caller = $Data['caller'];
        $ObjectKeywordData = $Data['ObjectKeywordData'];
        $this->Data = $Data;
    }

    private function ImportDeCSInner() {
        $SimpleLifeMessage = new SimpleLifeMessage('[Exploring Keyword] ' . $this->Description);
        $ObjectKeyword = new ObjectKeywordIntegration($this->Data['ObjectKeywordData']['keyword'], $this->Data['ObjectKeywordData']['langs'], $this->Data['ObjectKeywordData']['MaxImports']);
        $DeCSImporter = new ControllerImportDeCS($ObjectKeyword, $this->ConnectionTimer, $SimpleLifeMessage, 1);
        $fun = $DeCSImporter->InnerObtain($this->Data['caller']);
        if ($fun) {
            return $fun;
        }
        $this->Result = $ObjectKeyword->getResults();
        $SimpleLifeMessage->SendAsLog();
    }

    ///////////////////////////////////////////////////
    //IMPORT RESULTS FUNCTION
    //////////////////////////////////////////////////

    private function ImportResultsNumberDataIntegrity($Data) {
        $Data = json_decode($Data, true);
        $local = $Data['local'];
        $global = $Data['global'];
        $this->Data = $Data;
    }

    private function ImportResultsNumberInner() {
        $SimpleLifeMessage = new SimpleLifeMessage('[Exploring ResultsNumber] ' . $this->Description);
        $ObjectResult = new ObjectResult();
        foreach ($this->Data as $key => $ObjectResultQuery) {
            $ObjectResultQuery = new ResultQuery($this->Data[$key]);
            $ObjectResult->AddResultQueryObject($ObjectResultQuery, $key);
        }
        $ResultsImporter = new ControllerImportResultsNumber($ObjectResult, $this->ConnectionTimer, $SimpleLifeMessage);
        $fun = $ResultsImporter->InnerObtain();
        if ($fun) {
            return $fun;
        }
        $this->Result = $ObjectResult->getResults();
    }

}

?>