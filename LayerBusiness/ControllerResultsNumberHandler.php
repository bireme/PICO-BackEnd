<?php

namespace LayerBusiness;

require_once(realpath(dirname(__FILE__)) . '/../InterfaceIntegration.php');
require_once(realpath(dirname(__FILE__)) . '/../SimpleLife/ManualExceptions.php');
require_once(realpath(dirname(__FILE__)) . '/../SimpleLife/SimpleLifeMessages.php');

use SimpleLife\SimpleLifeMessage;
use InterfaceIntegration;
use SimpleLife\SimpleLifeException;

class ControllerResultsNumberHandler {

    private $ObjectResultList;
    private $ResultsNumberProcessor;

    public function __construct($ObjectResultList,$ResultsNumberProcessor) {
        $this->ObjectResultList = $ObjectResultList;
        $this->ResultsNumberProcessor=$ResultsNumberProcessor;
    }
    
    public function obtainResultsNumber() {
        $results=$this->ResultsNumberFromIntegration();
        $this->ProcessResults($results);
    }

    private function ResultsNumberFromIntegration() {
        $queries = $this->ObjectResultList->getAllResultQueryObjects();
        $Data = array(
            'local' => $queries['local']->getQuery(),
            'global' => $queries['global']->getQuery()
        );
        $info = json_encode($Data);
        $ResultsNumberExplorer = new InterfaceIntegration('[Integration Report] ' . $info);
        return $ResultsNumberExplorer->ImportResultsNumber(json_encode($Data));
    }

    private function ProcessResults($results) {
        $results = json_decode($results, true);
        if (array_key_exists('Error', $results)) {
            try {
                $Error = $results['Error'];
                throw new SimpleLifeException(new \SimpleLife\PreviousIntegrationException($Error));
            } catch (SimpleLifeException $Ex) {
                return $Ex->PreviousUserErrorCode();
            }
        } else {
            $this->ResultsNumberProcessor->IntegrationResultsToObject($results['Data']);
        }
    }

}

?>