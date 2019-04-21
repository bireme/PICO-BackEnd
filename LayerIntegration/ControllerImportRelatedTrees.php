<?php

namespace LayerIntegration;

use \DOMDocument;

class ControllerImportRelatedTrees {

    private $params;
    private $connectionTimer;
    

    protected function addTimer($time) {
        $this->connectionTimer += $time;
    }
    public function getTimer(){
       return $this->connectionTimer;
   }
    
    public function __construct($DeCSObject, $tree_list, $langs) {
        $tree_list = $this->CleanRepeatedTrees($DeCSObject, $tree_list);
        $this->params = array('DeCSObject' => $DeCSObject, 'tree_list' => $tree_list, 'langs' => $langs);
    }

    public function ExploreRelatedTrees() {
        if (count($this->params['tree_list']) > 0) {
            echo '</br></br>Exploring Related Trees:</br>' . join($this->params['tree_list'], '</br>');
            $obj = new ControllerImportDeCS();

            try {
                $obj->obtainInfo($this->params);
            } catch (IntegrationExceptions $exc) {
            if ($exc->HandleError()) {
                $Ex = new JustReturnException();
                throw new IntegrationExceptions($Ex->build(),$exc->PreviousUserErrorCode());
            }
            }finally{
                $this->addTimer($obj->getTimer());
            }
            
        }
    }

    private function CleanRepeatedTrees($DeCSObject, $tree_list) {
        $usedTrees = $DeCSObject->Tree_List();
        $NewArr = array();
        foreach ($tree_list as $tree_id) {
            if (!(in_array($tree_id, $usedTrees))) {
                array_push($NewArr, $tree_id);
            }
        }
        return $NewArr;
    }

}

?>