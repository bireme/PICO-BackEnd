<?php

namespace LayerIntegration;

class ControllerImportDeCS {

    private $ObjectKeywordIntegration;
    private $langs;
    private $AllLangs = array('en', 'pt', 'es');
    private $SimpleLifeMessage;
    private $DeCSConnector;
    private $XMLExtractor;

    public function __construct($ObjectKeywordIntegration, $langs, $SimpleLifeMessage, $DeCSConnector, $XMLExtractor) {
        $this->ObjectKeywordIntegration = $ObjectKeywordIntegration;
        $this->langs = $langs;
        $this->SimpleLifeMessage = $SimpleLifeMessage;
        $this->DeCSConnector = $DeCSConnector;
        $this->XMLExtractor = $XMLExtractor;
    }

///////////////////////////////////////////////////////////////////
//PUBLIC FUNCTIONS
/////////////////////////////////////////////////////////////////// 

    public function ExploreTreeId($key, $IsMainTree, $ExploredTrees, &$results,$langs=NULL) {
        if ($IsMainTree) {
            $this->ExploreMainKeywordByLang($key, $ExploredTrees, $results);
        } else {
            $this->ExploreTreeIdByLang($key, $ExploredTrees, $results,$langs);
        }
    }

///////////////////////////////////////////////////////////////////
//INNER FUNCTIONS
/////////////////////////////////////////////////////////////////// 


    private function ExploreMainKeywordByLang($key, $ExploredTrees, &$results) {
        foreach ($this->AllLangs as $lang) {
            if ($fun = $this->ExploreTreeIdOneLang($key, $lang, true, $ExploredTrees, $results)) {
                return $fun;
            }
        }
    }
    private function ExploreTreeIdByLang($key, $ExploredTrees, &$results,$langs=NULL) {
        if(!(isset($langs))){
            $langs=$this->langs;
        }
        foreach ($langs as $lang) {
            if ($fun = $this->ExploreTreeIdOneLang($key, $lang, false, $ExploredTrees, $results)) {
                return $fun;
            }
        }
    }

    private function ExploreTreeIdOneLang($key, $lang, $IsMainTree, $ExploredTrees, &$results) {
        $XML = NULL;
        return $this->PerformPostRequest($key, $lang, $IsMainTree, $XML) ||
                $this->AnalyzeXMLresults($XML, $lang, $ExploredTrees, $results) ||
                $this->obtainResults($results);
    }

    private function PerformPostRequest($key, $lang, $IsMainTree, &$XML) {
        $this->DeCSConnector->setConnectionInfo($key, $lang, $IsMainTree);
        $this->XMLExtractor->setOperationTitle('key=' . $key . '// lang=' . $lang);
        return $this->DeCSConnector->POSTRequest($XML);
    }

    private function AnalyzeXMLresults($XML, $lang, $ExploredTrees, $results) {
        $this->XMLExtractor->DeCSImporterXMLExtract($XML, $lang, $ExploredTrees, $results);
    }

    private function obtainResults(&$results) {
        $results = $this->XMLExtractor->getResults(); //TreeIds Split By languages with terms, decs, trees, descendants
    }

}

?>