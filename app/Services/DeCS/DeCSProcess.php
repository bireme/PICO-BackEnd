<?php

namespace PICOExplorer\Services\DeCS;

use PICOExplorer\Services\ServiceModels\SubControllerModel;

class DeCSProcess extends SubControllerModel {

///////////////////////////////////////////////////////////////////
//PUBLIC FUNCTIONS
///////////////////////////////////////////////////////////////////

    public function setDataFromIntegration($objectKeyword, $inidata) {
        $results = array();
        $AsTerms = array();
        $this->orderDeCSListByKeywordLang($inidata, $results);
        $this->retrieveByTermFromSelectedLanguages($results, $objectKeyword->getMainLanguage(), $objectKeyword->getLang(), $AsTerms);
        $objectKeyword->setBaseData($AsTerms);
    }

///////////////////////////////////////////////////////////////////
//INNER FUNCTIONS
/////////////////////////////////////////////////////////////////// 

    private function getCompLang($mainlanguage, $langArr) {
        if (count($langArr) == 1) {
            return current((Array) $langArr);
        }
        if (in_array($mainlanguage, $langArr)) {
            return $mainlanguage;
        }
        if (in_array('en', $langArr)) {
            return 'en';
        }
        return current((Array) $langArr);
    }

    private function retrieveByTermFromSelectedLanguages($results, $mainlanguage, $langArr, &$AsTerms) {
        $UsedTrees = array();
        $lang = $this->getCompLang($mainlanguage, $langArr);
        foreach ($results as $tree_id => $TreeObject) {
            $treedata1 = array('tree_id' => $tree_id, 'tree' => $TreeObject);
            $treedata2 = NULL;
            $TermTitle = $this->CompareDescriptors($treedata1, $treedata2, $lang);
            $this->AddToContent($TermTitle, $UsedTrees, $AsTerms, $TreeObject, $tree_id);
            foreach ($results as $tree_id2 => $TreeObject2) {
                if ($tree_id == $tree_id2) {
                    continue;
                }
                $treedata1 = array('tree_id' => $tree_id, 'tree' => $TreeObject);
                $treedata2 = array('tree_id' => $tree_id2, 'tree' => $TreeObject2);
                $TermTitle = $this->CompareDescriptors($treedata1, $treedata2, $lang);
                $this->AddToContent($TermTitle, $UsedTrees, $AsTerms, $TreeObject2, $tree_id2);
            }
        }
    }

    private function AddToContent($TermTitle, &$UsedTrees, &$AsTerms, $TreeObject, $tree_id) {


        if (!(isset($TermTitle)) ?: !(strlen($TermTitle) > 0)) {
            return false;
        }
        if (in_array($tree_id, $UsedTrees)) {
            return false;
        }
        array_push($UsedTrees, $tree_id);
        if (!(array_key_exists($TermTitle, $AsTerms))) {
            $AsTerms[$TermTitle] = array();
        }
        if (!(array_key_exists($tree_id, $AsTerms[$TermTitle]))) {
            $AsTerms[$TermTitle][$tree_id] = array();
        }
        $AsTerms[$TermTitle][$tree_id] = $TreeObject['DeCSArr'];
    }

    private function CompareDescriptors($tree1, $tree2, $lang) {

        $tree_id1 = $tree1['tree_id'];
        $TermsArr1 = (Array) $tree1['tree']['TermsArr'];
        $tree_id2 = NULL;
        $TermsArr2 = NULL;
        $msg = '</br></br>lang(' . $lang . ') not fuound in';
        $alpha = 0;
        if (!(in_array($lang, array_keys($TermsArr1)))) {
            $msg = $msg . '  terms1(' . $tree_id1 . ')=' . json_encode($TermsArr1);
            $alpha = 1;
        }

        if (isset($tree2)) {
            $tree_id2 = $tree2['tree_id'];
            $TermsArr2 = (Array) $tree2['tree']['TermsArr'];
            if (!(in_array($lang, array_keys($TermsArr2)))) {
                $msg = $msg . ' terms2(' . $tree_id2 . ')=' . json_encode($TermsArr2);
                $alpha = 1;
            }
        }
        if ($alpha == 1) {
            echo $msg;
            return 'Undefined';
        }

        if ($this->IsEmptyTermsArr($TermsArr1)) {
            return 'Undefined';
        }
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

    private function IsEmptyTermsArr($TermsArr) {
        if (!(isset($TermsArr)) ?: !(is_array($TermsArr)) ?: count($TermsArr) == 0) {
            return true;
        } else {
            return false;
        }
    }

///////////////////////////////////////////////////////////////////
//ORDER BY TERM AND FIX DECS COMMAS
/////////////////////////////////////////////////////////////////// 


    private function orderDeCSListByKeywordLang($inidata, &$results) {
        foreach ($inidata['content'] as $tree_id => $TreeData) {
            $results[$tree_id] = array();
            $DeCSArrGlobal = array();
            $TermsArr = array();
            foreach ($TreeData['content'] as $lang => $ContentByLang) {
                $TermsArr[$lang] = $ContentByLang['term'];
                $DeCSArrGlobal[$lang] = array_merge(array(), $ContentByLang['DeCSArr']);
                array_push($DeCSArrGlobal[$lang], $TermsArr[$lang]);
            }
            $this->ExploreDescendants($TreeData['descendants'], $DeCSArrGlobal);
            $results[$tree_id]['TermsArr'] = $TermsArr;
            $results[$tree_id]['DeCSArr'] = $this->RemoveCommasFromDeCSArr($DeCSArrGlobal);
        }
    }

    private function ExploreDescendants($TreeDataArr, &$DeCSArrGlobal) {
        foreach ($TreeDataArr as $tree_id => $TreeData) {
            foreach ($TreeData['content'] as $lang => $ContentByLang) {
                $term = $ContentByLang['term'];
                $DeCSArr = $ContentByLang['DeCSArr'];
                array_push($DeCSArr, $term);
                $DeCSArrGlobal[$lang] = array_merge($DeCSArrGlobal[$lang], $DeCSArr);
            }
            $this->ExploreDescendants($TreeData['descendants'], $DeCSArrGlobal);
        }
    }

    private function RemoveCommasFromDeCSArr($DeCSArrGlobal) {
        $Result = array();
        foreach ($DeCSArrGlobal as $lang => $DeCSLanObject) {
            $Result[$lang]=array();
            foreach ($DeCSLanObject as $key => $DeCSWord) {
                $DeCSWordSplitByComma = explode(', ', $DeCSWord);
                if (count($DeCSWordSplitByComma) > 1) {
                    $NumberOfCommas = count($DeCSWordSplitByComma) - 1;
                    $DeCSWordReOrderedWithoutComma = '';
                    while ($NumberOfCommas >= 0) {
                        if (strlen($DeCSWordReOrderedWithoutComma) > 0) {
                            $DeCSWordReOrderedWithoutComma = $DeCSWordReOrderedWithoutComma . ' ';
                        }
                        $DeCSWordReOrderedWithoutComma = $DeCSWordReOrderedWithoutComma . $DeCSWordSplitByComma[$NumberOfCommas];
                        $NumberOfCommas--;
                    }
                    $DeCSWord = $DeCSWordReOrderedWithoutComma;
                }
                $Result[$lang][$key]=$DeCSWord;
            }
        }
        return $Result;
    }

}

?>
