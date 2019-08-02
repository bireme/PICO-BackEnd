<?php

namespace PICOExplorer\Services\DeCS;

abstract class DeCSInfoProcessor extends DeCSInternalConnector
{

    protected $attributes = [];

    protected function ProcessIntegrationResults($IntegrationData)
    {
        $lang = $this->getCompLang($this->model->InitialData['mainLanguage'], $this->model->InitialData['langs']);
        $ProcessedData=[];
        foreach ($IntegrationData as $keyword => $KeywordObj) {
            $UsedTrees = [];
            $AsTerms = [];
            foreach ($KeywordObj as $tree_id => $TreeObject) {
                $treedata1 = array('tree_id' => $tree_id, 'tree' => $TreeObject);
                $treedata2 = NULL;
                $TermTitle = $this->CompareDescriptors($treedata1, $treedata2, $lang);
                $this->AddToContent($TermTitle, $UsedTrees, $AsTerms, $TreeObject, $tree_id);
                foreach ($KeywordObj as $tree_id2 => $TreeObject2) {
                    if ($tree_id == $tree_id2) {
                        continue;
                    }
                    $treedata1 = array('tree_id' => $tree_id, 'tree' => $TreeObject);
                    $treedata2 = array('tree_id' => $tree_id2, 'tree' => $TreeObject2);
                    $TermTitle = $this->CompareDescriptors($treedata1, $treedata2, $lang);
                    $this->AddToContent($TermTitle, $UsedTrees, $AsTerms, $TreeObject2, $tree_id2);
                }
            }
            $ProcessedData[$keyword]=$AsTerms;
        }
        return $ProcessedData;
    }

    protected function IntegrationResultsToSavedData(array $IntegrationResults, string $keyword){
        $SavedData = $this->model->SavedData??[];
        if(!(in_array($keyword,$SavedData))){
            $SavedData[$keyword]=$IntegrationResults;
        }else{
            $tmpData=$IntegrationResults;
            foreach($tmpData as $lang => $Data){
                $SavedData[$keyword][$lang]=$Data;
            }
        }
        $this->UpdateModel(__METHOD__ . '@' . get_class($this),['SavedData'=>$SavedData],[]);
    }

///////////////////////////////////////////////////////////////////
//INNER FUNCTIONS
///////////////////////////////////////////////////////////////////

    private function getCompLang($mainlanguage, $langArr)
    {
        if (count($langArr) == 1) {
            return current((Array)$langArr);
        }
        if (in_array($mainlanguage, $langArr)) {
            return $mainlanguage;
        }
        if (in_array('en', $langArr)) {
            return 'en';
        }
        return current((Array)$langArr);
    }

    private function AddToContent($TermTitle, &$UsedTrees, &$AsTerms, $TreeObject, $tree_id)
    {

        if (!(isset($TermTitle)) ?: !(strlen($TermTitle) > 0)) {
            return;
        }
        if (in_array($tree_id, $UsedTrees)) {
            return;
        }
        array_push($UsedTrees, $tree_id);
        if (!(array_key_exists($TermTitle, $AsTerms))) {
            $AsTerms[$TermTitle] = [];
        }
        $DeCSArr=[];
        foreach($TreeObject as $lang => $content){
            if(!(in_array($lang,$this->model->InitialData['langs']))){
                continue;
            }
            $term = $content['term'];
            $decs = $content['decs'];
            $DeCSArr=array_merge($DeCSArr,[$term],$decs);
        }
        $AsTerms[$TermTitle] = $DeCSArr;
    }

    private function getTermsArr($tree)
    {
        $results = [];
        foreach ($tree['tree'] as $lang => $content) {
            $results[$lang]=$content['term'];
        }
        return $results;
    }


    private function CompareDescriptors($tree1, $tree2, $lang)
    {
        $tree_id1 = $tree1['tree_id'];
        $TermsArr1 = $this->getTermsArr($tree1);
        $tree_id2 = NULL;
        $TermsArr2 = NULL;
        $msg = '</br></br>lang(' . $lang . ') not found in';
        $alpha = 0;
        if (!(in_array($lang, array_keys($TermsArr1)))) {
            $msg = $msg . '  terms1(' . $tree_id1 . ')=' . json_encode($TermsArr1);
            $alpha = 1;
        }
        if (isset($tree2)) {
            $tree_id2 = $tree2['tree_id'];
            $TermsArr2 = $this->getTermsArr($tree2);
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

    private function IsEmptyTermsArr($TermsArr)
    {
        if (!(isset($TermsArr)) ?: !(is_array($TermsArr)) ?: count($TermsArr) == 0) {
            return true;
        } else {
            return false;
        }
    }

}
