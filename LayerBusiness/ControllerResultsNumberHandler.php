<?php

namespace LayerBusiness;

require_once(realpath(dirname(__FILE__)) . '/../LayerFacades/IntegrationPICOExplorer.php');

class ControllerResultsNumberHandler {

    private $ObjectResultList;
    private $ResultsNumberProcessor;

    public function __construct($ObjectResultList, $ResultsNumberProcessor) {
        $this->ObjectResultList = $ObjectResultList;
        $this->ResultsNumberProcessor = $ResultsNumberProcessor;
    }

    ///////////////////////////////////////////////////////////////////
    //PUBLIC FUNCTIONS
    ///////////////////////////////////////////////////////////////////

    public function obtainResultsNumber() {
        $results = $this->ResultsNumberFromIntegration();
    }

    ///////////////////////////////////////////////////////////////////
    //INNER FUNCTIONS
    /////////////////////////////////////////////////////////////////// 

    private function ResultsNumberFromIntegration() {
        $queries = $this->ObjectResultList->getAllResultQueryObjects();
        $ResultQueryRequest = array();
        foreach ($queries as $key => $queryObj) {
            $ResultQueryRequest[$key] = $queryObj->getQuery();
        }

        $Data = array(
            'ResultQueryRequest' => $ResultQueryRequest
        );

        $ResultsNumberExplorer = new \IntegrationPICOExplorer(json_encode($Data));
        return $this->ProcessResults($ResultsNumberExplorer->ImportResultsNumber());
    }

    private function ProcessResults($resultdata) {
        $results = json_decode($resultdata, true);
        try {
            if (!(is_array($results))) {
                throw new SimpleLifeException(new \SimpleLife\PreviousIntegrationException($results['Error']));
            }
            if (array_key_exists('Error', $results)) {
                throw new SimpleLifeException(new \SimpleLife\PreviousIntegrationException($results['Error']));
            }
            return $this->ResultsNumberProcessor->IntegrationResultsToObject($results['Data']);
        } catch (SimpleLifeException $Ex) {
            return $Ex->PreviousUserErrorCode();
        }
    }

}

?>