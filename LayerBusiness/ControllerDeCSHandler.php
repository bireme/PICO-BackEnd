<?php

namespace LayerBusiness;

require_once(realpath(dirname(__FILE__)) . '/../LayerBusiness/ControllerDeCSDataProcessor.php');
require_once(realpath(dirname(__FILE__)) . '/../LayerFacades/IntegrationPICOExplorer.php');

class ControllerDeCSHandler {

    private $ObjectKeyWord;
    private $DeCSDataProcessor;

    public function __construct($DeCSDataProcessor) {
        $this->DeCSDataProcessor = $DeCSDataProcessor;
    }

    ///////////////////////////////////////////////////////////////////
    //PUBLIC FUNCTIONS
    ///////////////////////////////////////////////////////////////////

    public function setObjectKeyword($ObjectKeyWord) {
        $this->ObjectKeyWord = $ObjectKeyWord;
    }

    public function obtainDeCS() {
        return $this->DeCSFromIntegration();
    }

    ///////////////////////////////////////////////////////////////////
    //INNER FUNCTIONS
    /////////////////////////////////////////////////////////////////// 

    private function DeCSFromIntegration() {
        $Data = array(
            'keyword' => $this->ObjectKeyWord->getKeyword(),
            'langs' => $this->ObjectKeyWord->getLang(),
        );
        $DeCSExplorer = new \IntegrationPICOExplorer(json_encode($Data));
        return $this->ProcessResults($DeCSExplorer->ImportDeCS());
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
            $this->DeCSDataProcessor->setDataFromIntegration($this->ObjectKeyWord, $results['Data']);
        } catch (SimpleLifeException $Ex) {
            return $Ex->PreviousUserErrorCode();
        }
    }

}

?>