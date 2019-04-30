<?php

namespace LayerEntities;

class DeCSDescriptor {

    private $tree_id;
    private $term;
    private $DeCS;
    private $Complete;
    private $Descendants;

    public function __construct($tree_id, $term, $DeCS) {
        $this->tree_id = $tree_id;
        $this->term = $term;
        $this->DeCS = $DeCS;
        $this->Complete = false;
        $this->Descendants = array();
        $this->isSelected = false;
    }

    function IsComplete() {
        return $this->Complete;
    }

    function setComplete() {
        $this->Complete = true;
    }

    public function ID() {
        return $this->tree_id;
    }

    public function getTerm() {
        return $this->term;
    }

    public function SetDescendants($Descendants) {
        $this->Descendants = $Descendants;
    }

    public function GetDescendants() {
        return $this->Descendants;
    }

    public function GetDescendantsAsString() {
        return '{' . join($this->Descendants, ', ') . '}';
    }

    public function setDeCS($DeCS) {
        $this->DeCS = array_unique(array_merge($this->DeCS, $DeCS));
    }

    public function getDeCS() {
        return $this->DeCS;
    }

}

?>