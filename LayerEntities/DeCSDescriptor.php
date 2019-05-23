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
        $DeCS=$this->DeCScomma($DeCS);
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
        return '{' . join($this->Descendants, ' , ') . '}';
    }

    public function setDeCS($DeCS) {
        $this->DeCS = array_unique(array_merge($this->DeCS, $DeCS));
    }

    private function DeCScomma($DeCS) {
        foreach ($DeCS as $key=>$subDeCS) {
            
            $tmp = explode(', ', $subDeCS);
            if (count($tmp) > 1) {
                $countx = count($tmp)-1;
                $msgx = '';
                while ($countx >= 0) {
                    if(strlen($msgx)>0){
                        $msgx = $msgx. ' ';
                    }       
                    $msgx = $msgx . $tmp[$countx];
                    $countx--;
                }
                $DeCS[$key] = $msgx;
            }
        }
        return $DeCS;
    }

    public function getDeCS() {
        return $this->DeCS;
    }

}

?>