<?php

namespace LayerIntegration;

use \DOMDocument;

/**
 * @access public
 * @author Daniel Nieto
 * @package LayerIntegration
 */
class ControllerImportRelatedTrees {

    private $params;

    public function __construct($DeCSObject, $tree_list,$langs) {
        $tree_list=$this->CleanRepeatedTrees($DeCSObject,$tree_list);
        $this->params = array('DeCSObject'=>$DeCSObject,'tree_list'=>$tree_list,'langs'=>$langs);
    }


    
    public function ExploreRelatedTrees() {
        if (count($this->params['tree_list']) > 0) {
            echo '</br></br>Exploring Related Trees:</br>' . join($this->params['tree_list'], '</br>');
            $obj = new ControllerImportDeCS();
            $obj->obtainInfo($this->params);
        }
    }

    private function CleanRepeatedTrees($DeCSObject,$tree_list) {
        $usedTrees=$DeCSObject->Tree_List();
        $NewArr = array();
        foreach ($tree_list as $tree_id) {
            if(!(in_array($tree_id, $usedTrees))){
                array_push($NewArr, $tree_id);
            }
        }
        return $NewArr;
    }

}

?>