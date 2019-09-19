<?php

namespace PICOExplorer\Services\DeCS;

use PICOExplorer\Models\DataTransferObject;

abstract class DeCSInfoProcessor extends DeCSQueryProcessor
{

    protected function ProcessIntegrationResults(DataTransferObject $DTO, array $IntegrationData,string $mainLanguage,array $langArr)
    {
        $lang = $this->getCompLang($mainLanguage,$langArr);
        $ProcessedData=[];
        foreach ($IntegrationData as $keyword => $KeywordObj) {
            $UsedTrees = [];
            $AsTerms = [];
            foreach ($KeywordObj as $tree_id => $TreeObject) {
                $treedata1 = array('tree_id' => $tree_id, 'tree' => $TreeObject);
                $treedata2 = NULL;
                $TermTitle = $this->CompareDescriptors($lang,$treedata1, $treedata2);
                $this->AddToContent($TermTitle, $UsedTrees, $AsTerms, $TreeObject, $tree_id,$langArr);
                foreach ($KeywordObj as $tree_id2 => $TreeObject2) {
                    if ($tree_id == $tree_id2) {
                        continue;
                    }
                    $treedata1 = array('tree_id' => $tree_id, 'tree' => $TreeObject);
                    $treedata2 = array('tree_id' => $tree_id2, 'tree' => $TreeObject2);
                    $TermTitle = $this->CompareDescriptors($lang,$treedata1, $treedata2);
                    $this->AddToContent($TermTitle, $UsedTrees, $AsTerms, $TreeObject2, $tree_id2,$langArr);
                }
            }
            $ProcessedData[$keyword]=$AsTerms;
        }
        $DTO->SaveToModel(get_class($this),['ProcessedResults' => $ProcessedData]);
    }

///////////////////////////////////////////////////////////////////
//INNER FUNCTIONS
///////////////////////////////////////////////////////////////////

    private function getCompLang(string $mainlanguage, array $langArr)
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

    private function AddToContent(string $TermTitle=null,array &$UsedTrees,array &$AsTerms,array $TreeObject,string $tree_id,array $langArr)
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
            if(!(in_array($lang,$langArr))){
                continue;
            }
            $term = $content['term'];
            $decs = $content['decs'];
            $DeCSArr=array_merge($DeCSArr,[$term],$decs);
        }
        $AsTerms[$TermTitle] = $DeCSArr;
    }

    private function getTermsArr(array $tree)
    {
        $results = [];
        foreach ($tree['tree'] as $lang => $content) {
            if($lang!=='descendants'){
                $results[$lang]=$content['term'];
            }
        }
        return $results;
    }


    private function CompareDescriptors(string $lang,array $tree1,array $tree2=null)
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

    private function IsEmptyTermsArr(array $TermsArr=null)
    {
        if (!(isset($TermsArr)) ?: !(is_array($TermsArr)) ?: count($TermsArr) == 0) {
            return true;
        } else {
            return false;
        }
    }

}
