<?php

require_once(realpath(dirname(__FILE__)) . '/../LayerBusiness/ControllerDeCSQueryProcessor.php');
require_once(realpath(dirname(__FILE__)) . '/../LayerBusiness/ControllerQueryBuilder.php');
require_once(realpath(dirname(__FILE__)) . '/../LayerEntities/ObjectQuery.php');

use LayerBusiness\ControllerDeCSQueryProcessor;
use LayerBusiness\ControllerQueryBuilder;
use LayerEntities\ObjectQuery;

class FacadeQueryBuilder {

    private $SimpleLifeMessage;
    private $ConnectionTimer;
    private $data;
    private $mainLanguage;
    private $DeCSqueryProcessor;
    private $ObjectQuery;
    private $queryBuilder;
    private $results;
    private $QuerySplit;
    private $SelectedDescriptors;
    private $ImproveSearchQuery;
    private $PICOName;

    public function __construct($data, $ConnectionTimer, $SimpleLifeMessage) {
        $this->data = $data;
        $this->SimpleLifeMessage = $SimpleLifeMessage;
        $this->ConnectionTimer = $ConnectionTimer;
    }

    ///////////////////////////////////////////////////////////////////
    //PUBLIC FUNCTIONS
    /////////////////////////////////////////////////////////////////// 

    public function getPICOExplorerResults() {
        return $this->ObjectQuery->getResults();
    }

    public function BuildPICOExplorerResults() {
        return $this->CheckDataIntegrity() ||
                $this->BuildObjects() ||
                $this->queryBuilder->BuildnewQuery() ||
                $this->queryBuilder->ImproveSearch();
    }

    ///////////////////////////////////////////////////////////////////
    //INNER FUNCTIONS
    /////////////////////////////////////////////////////////////////// 

    private function CheckDataIntegrity() {
        $results = $this->data['results'];
        $QuerySplit = $this->data['QuerySplit'];
        $this->results = json_decode($results, true);
        $this->QuerySplit = json_decode($QuerySplit, true);
        $this->SelectedDescriptors = $this->data['SelectedDescriptors'];
        $this->ImproveSearchQuery = $this->data['ImproveSearchQuery'];
        $this->PICOnum = $this->data['PICOnum'];
        $PICOLIST = array('Problem', 'Intervention', 'Comparison', 'Outcome', 'Tipe of Study', 'General');
        $this->PICOname = $PICOLIST[$this->PICOnum - 1];
        $this->mainLanguage = $this->data['mainLanguage'];
    }

    private function BuildObjects() {
        $this->DeCSqueryProcessor = new ControllerDeCSQueryProcessor(NULL, NULL, NULL);
        $this->ObjectQuery = new ObjectQuery($this->results, $this->SelectedDescriptors, $this->ImproveSearchQuery, $this->PICOName, $this->QuerySplit);
        $this->queryBuilder = new ControllerQueryBuilder($this->ObjectQuery, $this->DeCSqueryProcessor);
        $this->SimpleLifeMessage->AddAsNewLine('$PICO=' . $this->PICOname . ' $querysplit = ' . json_encode($this->QuerySplit) . ' SelectedDescriptors = ' . json_encode($this->SelectedDescriptors) . ' ImproveSearchQuery = ' . json_encode($this->ImproveSearchQuery));
    }

}

?>