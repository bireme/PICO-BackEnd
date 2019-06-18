<?php

require_once(realpath(dirname(__FILE__)) . '/../LayerEntities/ObjectResultList.php');
require_once(realpath(dirname(__FILE__)) . '/../LayerBusiness/ControllerResultsNumberHandler.php');
require_once(realpath(dirname(__FILE__)) . '/../LayerBusiness/ControllerResultsNumberProcessor.php');

use LayerBusiness\ControllerResultsNumberProcessor;
use LayerBusiness\ControllerResultsNumberHandler;
use LayerEntities\ObjectResultList;

class FacadeResultsNumberObtainer {

    private $SimpleLifeMessage;
    private $ConnectionTimer;
    private $ObjectResultList;
    private $data;
    private $ResultsNumberProcessor;
    private $ResultsNumberObtainer;
    private $queryobject;
    private $PICOnum;
    private $PICOname;
    private $mainLanguage;

    public function __construct($data, $ConnectionTimer, $SimpleLifeMessage) {
        $this->data = $data;
        $this->SimpleLifeMessage = $SimpleLifeMessage;
        $this->ConnectionTimer = $ConnectionTimer;
    }

    ///////////////////////////////////////////////////////////////////
    //PUBLIC FUNCTIONS
    /////////////////////////////////////////////////////////////////// 

    public function getPICOExplorerResults() {
        return $this->ObjectResultList->getResults();
    }

    public function BuildPICOExplorerResults() {
        return $this->CheckDataIntegrity() ||
                $this->BuildObjects() ||
                $this->ResultsNumberProcessor->BuildQueries() ||
                $this->ResultsNumberObtainer->obtainResultsNumber();
    }

    ///////////////////////////////////////////////////////////////////
    //INNER FUNCTIONS
    /////////////////////////////////////////////////////////////////// 

    private function CheckDataIntegrity() {
        $this->queryobject = $this->data['queryobject'];
        $this->PICOnum = $this->data['PICOnum'];
        $PICOLIST = array('Problem', 'Intervention', 'Comparison', 'Outcome', 'Tipe of Study', 'General');
        $this->PICOname = $PICOLIST[$this->PICOnum - 1];
        $this->mainLanguage = $this->data['mainLanguage'];
    }

    private function BuildObjects() {
        $this->ObjectResultList = new ObjectResultList($this->PICOnum, $this->queryobject, $this->PICOname);
        $this->ResultsNumberProcessor = new ControllerResultsNumberProcessor($this->ObjectResultList);
        $this->ResultsNumberObtainer = new ControllerResultsNumberHandler($this->ObjectResultList, $this->ResultsNumberProcessor);
        $this->SimpleLifeMessage->AddAsNewLine('$PICO=' . $this->PICOname . ' $queryObject = ' . json_encode($this->queryobject));
    }

}

?>