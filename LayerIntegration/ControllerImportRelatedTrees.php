<?php

namespace LayerIntegration;

class ControllerImportRelatedTrees {

    private $params;

    public function __construct($ObjectKeyword, $SimpleLifeMessage, $timeSum) {
        $this->params = array('ObjectKeyword' => $ObjectKeyword, 'SimpleLifeMessage' => $SimpleLifeMessage, 'timeSum' => $timeSum);
    }

    public function ExploreRelatedTrees() {
        $RelatedList = $this->params ['ObjectKeyword']->GetAllUnexploredTrees();
        if (count($RelatedList) > 0) {
            $tree_id = $RelatedList[count($RelatedList) - 1];
            $obj = new ControllerImportDeCS();
            $this->params['tree_id'] = $tree_id;
            $fun=$obj->obtainInfo($this->params);
            if($fun){
                return $fun;
            }
        }
    }

}

?>