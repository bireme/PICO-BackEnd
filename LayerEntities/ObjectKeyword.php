<?php

namespace LayerEntities;

require_once('DeCSDescriptor.php');
require_once(realpath(dirname(__FILE__)) . '/../SimpleLife/SimpleLifeMessages.php');

use SimpleLife\SimpleLifeMessage;
use LayerEntities\DeCSDescriptor;

class ObjectKeyword {

    private $keyword;
    private $langArr;
    private $InMainTree;
    private $Completed;
    private $basedata;
    private $ConnectionTime;

    public function getConnectionTime() {
        return $this->ConnectionTime;
    }
    
    public function setPreviousBaseData($results) {
        $this->basedata=$results;
    }

    public function setBaseData($results) {
        $results = $this->ProcessResults($results);
        $this->basedata = $this->orderDeCSListByTerm($results, $this->mainlanguage, $this->langArr);
    }

    public function SetAsCompleted() {
        $this->Completed = true;
    }

    public function IsCompleted() {
        return $this->Completed;
    }

    public function __construct($keyword, $langArr, $mainlanguage) {
        $this->keyword = $keyword;
        $this->mainlanguage = $mainlanguage;
        $this->langArr = $langArr;
        $this->Completed = false;
    }

    public function getLang() {
        return $this->langArr;
    }

    public function getKeyword() {
        return $this->keyword;
    }

    //------------------------------------------------------------
    //------------------------------------------------------------
    //-THIS SECTION CONTAINS RESULTS AND SUMMARIES
    //------------------------------------------------------------
    //------------------------------------------------------------

    private function ProcessResults($inidata) {
        $content = array();
        $this->ConnectionTime = $inidata['ConnectionTime'];
        foreach ($inidata['content'] as $tree_id => $TreeObject) {
            $DeCSArrTotal = $TreeObject['DeCS'];
            $TermsArr = $TreeObject['TermsArr'];
            $Descendants = $TreeObject['descendants'];
            $this->ExploreDescendants($Descendants, $DeCSArrTotal);
            $langs = array_keys($DeCSArrTotal);
            foreach ($langs as $lang) {
                $DeCSArrTotal[$lang] = array_unique($DeCSArrTotal[$lang]);
            }
            $content[$tree_id] = array('TermsArr' => $TermsArr, 'DeCSArr' => $DeCSArrTotal);
        }
        return $content;
    }

    private function ExploreDescendants($Descendants, $DeCSArrTotal) {
        foreach ($Descendants as $tree_id => $TreeObject) {
            $DeCSArr = $TreeObject['DeCS'];
            $langs = array_unique(array_merge(array_keys($DeCSArrTotal), array_keys($DeCSArr)));
            foreach ($langs as $lang) {
                $DeCSArrTotal[$lang] = array_merge($DeCSArrTotal[$lang], $DeCSArr[$lang]);
            }
            $Descendants = $TreeObject['descendants'];
            $this->ExploreDescendants($Descendants, $DeCSArrTotal);
        }
    }

    private function AddToContent($TermTitle, &$UsedTrees, &$Result, $TreeObject, $tree_id) {
        if (!(isset($TermTitle)) ?: !(strlen($TermTitle) > 0)) {
            return false;
        }
        if (in_array($tree_id, $UsedTrees)) {
            return false;
        }
        array_push($UsedTrees, $tree_id);
        if (!(array_key_exists($TermTitle, $Result))) {
            $Result[$TermTitle] = array();
        }
        if (!(array_key_exists($tree_id, $Result[$TermTitle]))) {
            $Result[$TermTitle][$tree_id] = array();
        }
        $Result[$TermTitle][$tree_id] = $TreeObject['DeCSArr'];
    }

    private function orderDeCSListByTerm($results, $mainlanguage, $langArr) {
        $Result = array();
        $UsedTrees = array();
        foreach ($results as $tree_id => $TreeObject) {
            $TermTitle = $this->CompareDescriptors(array('tree_id' => $tree_id, 'tree' => $TreeObject), NULL, $mainlanguage);
            $this->AddToContent($TermTitle, $UsedTrees, $Result, $TreeObject, $tree_id);
            foreach ($results as $tree_id2 => $TreeObject2) {
                if ($tree_id == $tree_id2) {
                    continue;
                }
                $TermTitle = $this->CompareDescriptors(array('tree_id' => $tree_id, 'tree' => $TreeObject), array('tree_id' => $tree_id2, 'tree' => $TreeObject2), $mainlanguage);
                $this->AddToContent($TermTitle, $UsedTrees, $Result, $TreeObject2, $tree_id2);
            }
        }
        return $Result;
    }

    private function getCompLang($TermsArr1, $TermsArr2, $mainlanguage) {
        $keys1 = array_keys($TermsArr1);
        if ($this->IsEmptyTermsArr($TermsArr2)) {
            if (in_array($mainlanguage, $keys1)) {
                return $mainlanguage;
            } else {
                return current((Array) $keys1);
            }
        } else {
            $keys2 = array_keys($TermsArr2);
            $langs = array_intersect($keys1, $keys2);
            if (in_array($mainlanguage, $langs)) {
                return $mainlanguage;
            } else {
                if (count($langs) == 0) {
                    return current((Array) $keys1);
                } else {
                    return current((Array) $langs);
                }
            }
        }
    }

    private function IsEmptyTermsArr($TermsArr) {
        if (!(isset($TermsArr)) ?: !(is_array($TermsArr)) ?: count($TermsArr) == 0) {
            return true;
        } else {
            return false;
        }
    }

    private function CompareDescriptors($tree1, $tree2, $mainlanguage) {
        $tree_id1 = $tree1['tree_id'];
        $tree_id2 = $tree2['tree_id'];
        $TermsArr1 = (Array) $tree1['tree']['TermsArr'];
        $TermsArr2 = (Array) $tree2['tree']['TermsArr'];
        if ($this->IsEmptyTermsArr($TermsArr1)) {
            return 'Undefined';
        }
        $lang = $this->getCompLang($TermsArr1, $TermsArr2, $mainlanguage);
        if (!($this->IsEmptyTermsArr($TermsArr2))) {
            if ($TermsArr1[$lang] == $TermsArr2[$lang]) {
                return $TermsArr1[$lang];
            } else {
                return NULL;
            }
        } else {
            return $TermsArr1[$lang];
        }
    }

    private function getCompLangs($mainlanguage, $langArr) {
        return array_unique(array_merge(array($mainlanguage), $langArr));
    }

    public function getFullDeCSListKeyword() {
        return array(
            'content' => $this->basedata,
            'lang' => $this->langArr,
        );
    }

    public function KeyWordInfo($SimpleLifeMessage) {
        $SimpleLifeMessage->AddEmptyLine();
        $SimpleLifeMessage->AddAsNewLine('Keyword ' . $this->keyword . ':');
        $data = $this->basedata;
        foreach ($data as $term => $termObj) {
            foreach ($termObj as $tree_id => $DeCSObj) {
                foreach ($DeCSObj as $lang => $DeCSArr) {
                    $msg = $term . ' (' . $tree_id . ') [' . $lang . ']= {';
                    $msg = $msg . join($DeCSArr, ', ') . '}';
                    $SimpleLifeMessage->AddAsNewLine($msg);
                }
            }
        }
    }

}

?>