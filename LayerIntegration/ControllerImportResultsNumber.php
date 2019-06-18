<?php

namespace LayerIntegration;

class ControllerImportResultsNumber {

    private $BaseURL = 'http://pesquisa.bvsalud.org/portal/?count=20&q=';
    private $ObjectResult;
    private $SimpleLifeMessage;
    private $ResultsNumberConnector;
    private $XMLExtractor;

    public function __construct($ObjectResult, $SimpleLifeMessage, $ResultsNumberConnector, $XMLExtractor) {
        $this->ObjectResult = $ObjectResult;
        $this->SimpleLifeMessage = $SimpleLifeMessage;
        $this->ResultsNumberConnector = $ResultsNumberConnector;
        $this->XMLExtractor = $XMLExtractor;
    }

    ///////////////////////////////////////////////////////////////////
    //PUBLIC FUNCTIONS
    ///////////////////////////////////////////////////////////////////

    public function ExploreResultsNumber() {
        $List = $this->ObjectResult->getResultQueryList();
        foreach ($List as $ResultQuery) {
            if ($fun = $this->ExploreResultEquation($ResultQuery)) {
                return $fun;
            }
        }
    }

    ///////////////////////////////////////////////////////////////////
    //INNER FUNCTIONS
    ///////////////////////////////////////////////////////////////////

    private function ExploreResultEquation($ResultQuery) {
        $XML;
        return $this->PerformPostRequest($ResultQuery, $XML) ||
                $this->AnalyzeXMLresults($XML) ||
                $this->ProcessObtainedXML($ResultQuery);
    }

    private function PerformPostRequest($ResultQuery, &$XML) {
        $this->ResultsNumberConnector->setConnectionInfo($ResultQuery->getQuery());
        $this->XMLExtractor->setOperationTitle($ResultQuery->getQuery());
        return $this->ResultsNumberConnector->POSTRequest($XML);
    }

    private function AnalyzeXMLresults($XML) {
        
        $this->XMLExtractor->ResultsImporterXMLExtract($XML);
    }

    private function ProcessObtainedXML($ResultQuery) {
        $results = $this->XMLExtractor->getResults();
        $numFound = $results['numFound'];
        $ResultQuery->setResultsNumber($numFound);
    }

}

?>