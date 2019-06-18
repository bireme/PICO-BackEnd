<?php

require_once(realpath(dirname(__FILE__)) . '/../LayerIntegration/ControllerImportDeCS.php');
require_once(realpath(dirname(__FILE__)) . '/../LayerIntegration/ControllerImportRelatedTrees.php');
require_once(realpath(dirname(__FILE__)) . '/../LayerEntities/ObjectKeywordIntegration.php');
require_once(realpath(dirname(__FILE__)) . '/../LayerIntegration/ProxyReceiveDeCS.php');
require_once(realpath(dirname(__FILE__)) . '/../LayerIntegration/ControllerExtractXML.php');

use LayerIntegration\ControllerExtractXML;
use LayerIntegration\ProxyReceiveDeCS;
use LayerEntities\ObjectKeywordIntegration;
use LayerIntegration\ControllerImportDeCS;
use LayerIntegration\ControllerImportRelatedTrees;

class FacadeIntegrationDeCS {

    private $SimpleLifeMessage;
    private $ConnectionTimer;
    private $data;
    private $ObjectKeywordIntegration;
    private $DeCSImporter;
    private $DeCSRelatedTreesHandler;
    private $XMLExtractor;
    private $keyword;
    private $langs;
    private $MaxImports = array(
        'MaxDescendantsDepth' => 5,
        'MaxDirectDescendantsPerTrees' => 10,
        'MaxTreesPerKeyword' => 10);

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
                $this->DeCSRelatedTreesHandler->ExploreMainTree() ||
                $this->DeCSRelatedTreesHandler->ExploreRelatedTreesAndDescendants();
    }

    ///////////////////////////////////////////////////////////////////
    //INNER FUNCTIONS
    /////////////////////////////////////////////////////////////////// 

    public function getPICOExplorerResults() {
        $content= $this->ObjectKeywordIntegration->getResults();
        $results=array('content' => $content, 'ConnectionTime' => $this->ConnectionTimer->stop());
        return $results;
    }

    private function CheckDataIntegrity() {
        $this->keyword = $this->data['keyword'];
        $this->langs = $this->data['langs'];
    }

    private function BuildObjects() {
        $this->ObjectKeywordIntegration = new ObjectKeywordIntegration($this->keyword);
        $this->DeCSConnector = new ProxyReceiveDeCS($this->ConnectionTimer);
        $this->XMLExtractor = new ControllerExtractXML();
        $this->DeCSImporter = new ControllerImportDeCS($this->ObjectKeywordIntegration, $this->langs, $this->SimpleLifeMessage, $this->DeCSConnector,$this->XMLExtractor);
        $this->DeCSRelatedTreesHandler = new ControllerImportRelatedTrees($this->ObjectKeywordIntegration, $this->DeCSImporter, $this->SimpleLifeMessage,$this->MaxImports,$this->langs);
        $this->SimpleLifeMessage->AddAsNewLine('Langs=' . json_encode($this->langs) . ' keyword = "' . $this->keyword . '"');
    }

}

?>