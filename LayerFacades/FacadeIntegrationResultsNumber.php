<?php

require_once(realpath(dirname(__FILE__)) . '/../LayerIntegration/ControllerImportResultsNumber.php');
require_once(realpath(dirname(__FILE__)) . '/../LayerEntities/ObjectResult.php');
require_once(realpath(dirname(__FILE__)) . '/../LayerIntegration/ProxyReceiveResultsNumber.php');
require_once(realpath(dirname(__FILE__)) . '/../LayerIntegration/ControllerExtractXML.php');

use LayerIntegration\ControllerExtractXML;
use LayerIntegration\ProxyReceiveResultsNumber;
use LayerEntities\ObjectResult;
use LayerIntegration\ControllerImportResultsNumber;

class FacadeIntegrationResultsNumber {

    private $SimpleLifeMessage;
    private $ConnectionTimer;
    private $data;
    private $ObjectResult;
    private $ResultsNumberImporter;
    private $ResultQueryRequest;
    private $ResultsNumberConnector;
    private $XMLExtractor;

    public function __construct($data, $ConnectionTimer, $SimpleLifeMessage) {
        $this->data = $data;
        $this->SimpleLifeMessage = $SimpleLifeMessage;
        $this->ConnectionTimer = $ConnectionTimer;
    }

    ///////////////////////////////////////////////////////////////////
    //PUBLIC FUNCTIONS
    /////////////////////////////////////////////////////////////////// 

    public function BuildPICOExplorerResults() {
        return $this->CheckDataIntegrity() ||
                $this->BuildObjects() ||
                $this->ResultsNumberImporter->ExploreResultsNumber();
    }

    public function getPICOExplorerResults() {
        return $this->ObjectResult->getResults();
    }

    ///////////////////////////////////////////////////////////////////
    //INNER FUNCTIONS
    /////////////////////////////////////////////////////////////////// 

    private function CheckDataIntegrity() {
        $this->ResultQueryRequest = $this->data['ResultQueryRequest'];
    }

    private function BuildObjects() {
        $this->ObjectResult = new ObjectResult($this->ResultQueryRequest);
        $this->ResultsNumberConnector = new ProxyReceiveResultsNumber($this->ConnectionTimer);
        $this->XMLExtractor = new ControllerExtractXML();
        $this->ResultsNumberImporter = new ControllerImportResultsNumber($this->ObjectResult, $this->SimpleLifeMessage, $this->ResultsNumberConnector, $this->XMLExtractor);
        $this->SimpleLifeMessage->AddAsNewLine('ResultQueryRequest=' . json_encode($this->ResultQueryRequest));
    }

}

?>