<?php

namespace LayerBusiness;

require_once(realpath(dirname(__FILE__)) . '/../LayerBusiness/ControllerDeCSHandler.php');
require_once(realpath(dirname(__FILE__)) . '/../LayerEntities/ObjectKeyword.php');

class ControllerDeCSLooper {

    private $ObjectKeywordList;
    private $DeCSExplorer;

    public function __construct($ObjectKeywordList) {
        $this->ObjectKeywordList = $ObjectKeywordList;
        $DeCSDataProcessor = new ControllerDeCSDataProcessor();
        $this->DeCSExplorer = new ControllerDeCSHandler($DeCSDataProcessor);
    }

    ///////////////////////////////////////////////////////////////////
    //PUBLIC FUNCTIONS
    ///////////////////////////////////////////////////////////////////

    public function ExploreDeCSLooper() {
        $List = $this->getUnexploredKeywordList();
        foreach ($List as $ObjectKeyword) {
            if ($fun = $this->KeywordLoopExplorer($ObjectKeyword)) {
                return $fun;
            }
        }
    }

    ///////////////////////////////////////////////////////////////////
    //INNER FUNCTIONS
    /////////////////////////////////////////////////////////////////// 

    private function KeywordLoopExplorer($ObjectKeyword) {
        return $this->DeCSExplorer->setObjectKeyword($ObjectKeyword) ||
                $this->DeCSExplorer->obtainDeCS();
    }

    private function getUnexploredKeywordList() {
        $Result = array();
        foreach ($this->ObjectKeywordList->getKeywordList() as $keyword => $KeywordObj) {
            if ($KeywordObj->IsCompleted() == false) {
                $Result[$keyword] = $KeywordObj;
            }
        }
        return $Result;
    }

}

?>