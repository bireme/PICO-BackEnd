<?php

namespace LayerEntities;

require_once('DeCSDescriptorByLang.php');
use LayerEntities\DeCSDescriptorByLang;

class DeCSDescriptor {

    private $tree_id;
    private $Descendants;
    private $DescriptorsByLang;

    public function __construct($tree_id) {
        $this->DeCS = array();
        $this->langs = array();
        $this->DeCS['total'] = array();
        $this->Complete = false;
        $this->TermsArr = array();
        $this->Descendants = array();
        $this->tree_id = $tree_id;
        $this->IsExplored = false;
    }

    public function SetTermAndDeCS($term, $DeCS, $lang) {
        $this->setDeCS($DeCS, $lang);
        $this->TermsArr[$lang] = $term;
        array_push($this->langs, $lang);
    }

    public function ID() {
        return $this->tree_id;
    }

    public function getTermsArr() {
        return $this->TermsArr;
    }

    public function AddDescendant($Descendant, $tree_id) {
        $this->Descendants[$tree_id] = $Descendant;
    }

    public function GetDescendants() {
        return $this->Descendants;
    }




}

?>