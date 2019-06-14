<?php

namespace LayerEntities;

class DeCSDescriptor {

    private $tree_id;
    private $term;
    private $DeCS;
    private $TermsArr;
    private $langs;
    private $Complete;
    private $Descendants;
    private $IsExplored;

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

    public function setAsExplored() {
        $this->IsExplored = true;
    }

    public function isExplored() {
        return $this->IsExplored;
    }

    public function SetTermAndDeCS($term, $DeCS, $lang) {
        $this->setDeCS($DeCS, $lang);
        $this->TermsArr[$lang] = $term;
        array_push($this->langs, $lang);
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

    public function getTermsArr() {
        return $this->TermsArr;
    }

    public function AddDescendant($Descendant, $tree_id) {
        $this->Descendants[$tree_id] = $Descendant;
    }

    public function DescendantExists($tree_id) {
        if (array_key_exists($tree_id, $this->Descendants)) {
            return true;
        }
        return false;
    }

    public function GetDescendants() {
        return $this->Descendants;
    }

    public function getlangs() {
        return $this->langs;
    }

    public function setDeCS($DeCS, $lang) {
        $DeCS = $this->DeCScomma($DeCS);
        if (!(array_key_exists($lang, $this->DeCS))) {
            $this->DeCS[$lang] = array();
        }
        $this->DeCS[$lang] = array_unique(array_merge($this->DeCS[$lang], $DeCS));
        $this->DeCS['total'] = array_unique(array_merge($this->DeCS['total'], $DeCS));
    }

    private function DeCScomma($DeCS) {
        foreach ($DeCS as $key => $subDeCS) {
            $tmp = explode(', ', $subDeCS);
            if (count($tmp) > 1) {
                $countx = count($tmp) - 1;
                $msgx = '';
                while ($countx >= 0) {
                    if (strlen($msgx) > 0) {
                        $msgx = $msgx . ' ';
                    }
                    $msgx = $msgx . $tmp[$countx];
                    $countx--;
                }
                $DeCS[$key] = $msgx;
            }
        }
        return $DeCS;
    }

    public function getDeCS($lang = 'total') {
        if (!(array_key_exists($lang, $this->DeCS))) {
            return -1;
        }
        return $this->DeCS[$lang];
    }

    public function getResults($ShowTermsArr) {
        $key = $this->ID();
        $langs = $this->getlangs();
        $DeCSArr = array();
        $terms = $this->getTermsArr();
        foreach ($langs as $lang) {
            $DeCSArr[$lang] = $this->getDeCS($lang);
            array_push($DeCSArr[$lang], $terms[$lang]);
        }
        if ($ShowTermsArr == true) {
            return array('TermsArr' => $this->getTermsArr(), 'DeCS' => $DeCSArr, 'descendants' => $this->GetDescendants());
        } else {
            return array('DeCS' => $DeCSArr, 'descendants' => $this->GetDescendants());
        }
    }

}

?>