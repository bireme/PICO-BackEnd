<?php

require_once(realpath(dirname(__FILE__)) . '/../LayerBusiness/ControllerDeCSQueryProcessor.php');
require_once(realpath(dirname(__FILE__)) . '/../LayerBusiness/ControllerDeCSLooper.php');
require_once(realpath(dirname(__FILE__)) . '/../LayerBusiness/ControllerEquationChecker.php');
require_once(realpath(dirname(__FILE__)) . '/../LayerBusiness/ControllerDeCSMenuBuilder.php');
require_once(realpath(dirname(__FILE__)) . '/../LayerEntities/ObjectKeywordList.php');
require_once(realpath(dirname(__FILE__)) . '/../LayerEntities/ObjectQuerySplitList.php');

use LayerEntities\ObjectQuerySplitList;
use LayerEntities\ObjectKeywordList;
use LayerBusiness\ControllerEquationChecker;
use LayerBusiness\ControllerDeCSQueryProcessor;
use LayerBusiness\ControllerDeCSLooper;
use LayerBusiness\ControllerDeCSMenuBuilder;

class FacadeDeCSObtainer {

    private $PreviousData;
    private $query;
    private $langs;
    private $PICOnum;
    private $PICOname;
    private $mainLanguage;
    private $ObjectKeywordList;
    private $ObjectQuerySplitList;
    private $EquationProcessor;
    private $QueryProcesser;
    private $DeCSProcessor;
    private $DeCSMenuBuilder;
    private $SimpleLifeMessage;
    private $ConnectionTimer;
    private $data;

    public function __construct($data, $ConnectionTimer, $SimpleLifeMessage) {
        $this->data = $data;
        $this->SimpleLifeMessage = $SimpleLifeMessage;
        $this->ConnectionTimer = $ConnectionTimer;
    }

    ///////////////////////////////////////////////////////////////////
    //PUBLIC FUNCTIONS
    /////////////////////////////////////////////////////////////////// 

    public function BuildPICOExplorerResults() {
        $ExploredKeywords=NULL;
        return $this->CheckDataIntegrity() ||
                $this->BuildObjects() ||
                $this->EquationProcessor->CheckAndFixEquation() ||
                $this->queryProcesser->BuildKeywordList() ||
                $this->DeCSProcessor->ExploreDeCSLooper() ||
                $this->DeCSMenuBuilder->BuildHTML();
    }

    public function getPICOExplorerResults() {
        return $this->ObjectQuerySplitList->getResults();
    }

    ///////////////////////////////////////////////////////////////////
    //INNER FUNCTIONS
    /////////////////////////////////////////////////////////////////// 

    private function CheckDataIntegrity() {
        $this->PreviousData = $this->data['PreviousData'];
        $this->query = $this->data['query'];
        $this->langs = $this->data['langs'];
        $this->PICOnum = $this->data['PICOnum'];
        $PICOLIST = array('Problem', 'Intervention', 'Comparison', 'Outcome', 'Tipe of Study', 'General');
        $this->PICOname = $PICOLIST[$this->PICOnum - 1];
        $this->mainLanguage = $this->data['mainLanguage'];
    }

    private function BuildObjects() {
        $this->ObjectKeywordList = new ObjectKeywordList($this->langs, $this->mainLanguage);
        $this->ObjectQuerySplitList = new ObjectQuerySplitList($this->query, $this->ObjectKeywordList, $this->langs, $this->mainLanguage, $this->PICOname, $this->PICOnum);
        $this->EquationProcessor = new ControllerEquationChecker($this->ObjectQuerySplitList);
        $this->queryProcesser = new ControllerDeCSQueryProcessor($this->ObjectQuerySplitList, $this->ObjectKeywordList, $this->PreviousData);
        $this->DeCSProcessor = new ControllerDeCSLooper($this->ObjectKeywordList);
        $this->DeCSMenuBuilder = new ControllerDeCSMenuBuilder($this->ObjectQuerySplitList);
        $this->SimpleLifeMessage->AddAsNewLine('Langs=' . json_encode($this->langs) . ' query = "' . $this->query . '"');
    }

}

?>