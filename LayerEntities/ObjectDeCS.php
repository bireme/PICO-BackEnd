<?php

namespace LayerEntities;

require_once(realpath(dirname(__FILE__)) . '/../LayerEntities/DeCSDescriptor.php');

use LayerEntities\DeCSDescriptor;

class ObjectDeCS {

    private $DeCSList;
    private $descendants;

    public function getDescendants() {
        return $this->descendants;
    }

    public function setDescendants($descendants) {
        $this->descendants = $descendants;
    }

    public function __construct() {
        $this->descendants = array();
        $this->DeCSList = array();
    }

    public function setNull() {
        $this->DeCSList=NULL;
    }
    
    public function isDeCSSet() {
        return isset( $this->DeCSList);
    }

    public function ShowSummary() {
        echo '</br></br>-------------------------------------------</br>Summary of results</br>-------------------------------------------</br>';
        foreach ($this->DeCSList as $struct) {
            $struct->ShowSummary();
        }
    }

    private function DescriptorByTree($tree_id) {
        if (array_key_exists($tree_id, $this->DeCSList)) {
            return $this->DeCSList[$tree_id];
        }
        return NULL;
    }

    public function SetDescendantsByTree($tree_id, $Descendants) {
        $this->DeCSList[$tree_id]->SetDescendants($Descendants);
    }

    public function AddDeCSBasic($tree_id, $term, $DeCS, $isComplete) {
        $DeCSObject = $this->DescriptorByTree($tree_id);
        if (!(isset($DeCSObject))) {
            echo '<br>' . $tree_id . ' created with ' . count($DeCS) . ' synonyms';
            $DeCSObject = new DeCSDescriptor($tree_id, $term, $DeCS);
            $this->DeCSList[$tree_id] = $DeCSObject;
        } else {
            if ($DeCSObject->IsComplete() == false) {
                $DeCSObject->setDeCS($DeCS);
                echo '<br>' . $tree_id . ' added ' . count($DeCS) . ' synonyms';
                if ($isComplete == true) {
                    echo ' --> set as completed';
                    $DeCSObject->setComplete();
                }
            } else {
                echo '<br>' . $tree_id . ' is already complete';
            }
        }
    }

    public function GetTree_idByPosition($position) {
        return $this->DeCSList[(int) $position]->ID();
    }

    public function GetDeCSInfoGeneral() {
        $FinalArr = array();
        foreach ($this->DeCSList as $item) {
            $item->getDeCSInfo();
            array_push($FinalArr, $item);
        }
        return $FinalArr;
    }

    public function Tree_List() {
        return array_keys($this->DeCSList);
    }

    public function GetDeCSInfoByTree($tree_id) {
        if (array_key_exists($tree_id, $this->DeCSList)) {
            return $this->DeCSList[$tree_id]->getDeCSInfo();
        } else {
            return -1;
        }
    }

}

?>