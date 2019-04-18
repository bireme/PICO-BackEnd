<?php

namespace LayerEntities;

/**
 * @access public
 * @author Daniel Nieto
 * @package LayerIntegration
 */
class DeCSDescriptor {

    private $DeCSTitle;
    private $Tree_id;
    private $DeCS;
    private $Complete;
    private $Descendants;

    function IsComplete() {
        return $this->Complete;
    }

    function setComplete() {
        $this->Complete = true;
    }

    public function __construct($tree_id, $term, $DeCS) {
        $this->DeCSTitle = $term;
        $this->Tree_id = $tree_id;
        $this->DeCS = $DeCS;
        $this->Complete = false;
        $this->Descendants=array();
    }

    public function getDeCSInfo() {
        return array('term' => $DeCSTitle, 'tree_id' => $Tree_id, 'DeCS' => $DeCS,'Descendants' => $Descendants );
    }

    public function ID() {
        return $this->Tree_id;
    }
    
    public function SetDescendants($Descendants) {
        $this->Descendants=$Descendants;
    }

    public function ShowSummary() {
        echo '</br>' . $this->DeCSTitle . '(';
        echo $this->Tree_id . ') Descendants:{'.join($this->Descendants,', ').'}';
        echo '</br>' . join($this->DeCS, ", ") . '</br>';
    }
    
    public function setDeCS($DeCS) {
        $Final=array_merge($this->DeCS,$DeCS);
        $this->DeCS = array_unique($Final);
    }

    private function UniqueSynonym() {
        $this->DeCS = array_unique($this->DeCS);
    }

}

?>