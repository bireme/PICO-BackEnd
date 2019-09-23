<?php

namespace PICOExplorer\Services\DeCS;

use PICOExplorer\Exceptions\Exceptions\AppError\PreviousDataCouldNotBeDecoded;
use PICOExplorer\Models\DataTransferObject;
use PICOExplorer\Services\ServiceModels\ServiceEntryPoint;
use PICOExplorer\Services\ServiceModules\BuildHTMLTrait;
use Throwable;

abstract class DeCSInfoProcessor extends ServiceEntryPoint
{

    use BuildHTMLTrait;

    protected function DecodePreviousData(DataTransferObject $DTO, string $undecodedPreviousData=null)
    {
        $decodedPrevious = null;
        if ($undecodedPreviousData) {
            try {
                $decodedPrevious = json_decode($undecodedPreviousData, true);
            } catch (Throwable $ex) {
                throw new PreviousDataCouldNotBeDecoded(['PreviousData' => json_encode($undecodedPreviousData)], $ex);
            }
        } else {
            $decodedPrevious = [];
        }
        $DTO->SaveToModel(get_class($this), ['PreviousData' => $decodedPrevious]);
    }

    protected function MixWithOldData(DataTransferObject $DTO, array $IntegrationData)
    {
        $SavedData = $DTO->getAttr('PreviousData');
        foreach ($IntegrationData as $keyword => $keywordData) {
            if (!(array_key_exists($keyword, $SavedData))) {
                $SavedData[$keyword] = $IntegrationData[$keyword];
            }else {
                foreach ($keywordData as $lang => $content) {
                    if (!(array_key_exists($lang, $SavedData[$keyword]))) {
                        $SavedData[$keyword][$lang] = $content;
                    }else{
                        if ($lang !== 'descendants') {
                            if (!(array_key_exists('term', $SavedData[$keyword][$lang]))) {
                                $SavedData[$keyword][$lang]['term']=[];
                                $SavedData[$keyword][$lang]['decs']=[];
                            }
                            $SavedData[$keyword][$lang]['term'] = array_unique(array_merge($content['term'], $SavedData[$keyword][$lang]['term']));
                            $SavedData[$keyword][$lang]['decs'] = array_unique(array_merge($content['decs'], $SavedData[$keyword][$lang]['decs']));
                        } else {
                            $SavedData[$keyword][$lang] = array_unique(array_merge($content, $SavedData[$keyword][$lang]));
                        }
                    }
                }
            }
        }
        $DTO->SaveToModel(get_class($this), ['SavedData' => $SavedData]);
    }

    protected function BuildAsTerms(DataTransferObject $DTO, string $mainLanguage)
    {
        $ProcessedDescriptors = [];
        $ProcessedDeCS = [];
        $MixedData = $DTO->getAttr('SavedData');
        foreach ($MixedData as $keyword => $keywordData) {
            $UsedTrees = [];
            foreach ($keywordData as $tree_id => $TreeObject) {
                $treedata1 = array('tree_id' => $tree_id, 'tree' => $TreeObject);
                $treedata2 = NULL;
                $langArr = array_keys($TreeObject);
                $lang = $this->getCompLang($mainLanguage, $langArr);
                $TermTitle = $this->CompareDescriptors($lang, $treedata1, $treedata2);
                $this->AddToContent($TermTitle, $UsedTrees, $ProcessedDescriptors, $ProcessedDeCS, $TreeObject, $tree_id, $langArr, $keyword);
                foreach ($keywordData as $tree_id2 => $TreeObject2) {
                    if ($tree_id == $tree_id2) {
                        continue;
                    }
                    $langArrtwo = array_keys($TreeObject2);
                    $langtwo = $this->getCompLang($mainLanguage, $langArrtwo);
                    $treedata1 = array('tree_id' => $tree_id, 'tree' => $TreeObject);
                    $treedata2 = array('tree_id' => $tree_id2, 'tree' => $TreeObject2);
                    $TermTitle = $this->CompareDescriptors($langtwo, $treedata1, $treedata2);
                    $this->AddToContent($TermTitle, $UsedTrees, $ProcessedDescriptors, $ProcessedDeCS, $TreeObject2, $tree_id2, $langArrtwo, $keyword);
                }
            }
        }
        $DTO->SaveToModel(get_class($this), ['ProcessedDescriptors' => $ProcessedDescriptors, 'ProcessedDeCS' => $ProcessedDeCS]);
    }

    protected function BuildDeCSHTML(DataTransferObject $DTO, int $PICOnum)
    {
        $ProcessedDescriptors = $DTO->getAttr('ProcessedDescriptors');
        $ProcessedDeCS = $DTO->getAttr('ProcessedDeCS');
        //dd(['$ProcessedDescriptors'=>$ProcessedDescriptors,'$ProcessedDeCS'=>$ProcessedDeCS]);
        $DescriptorsHTML = $this->BuildHiddenField('descriptorsform', 'piconum', $PICOnum);
        $DescriptorsHTML = $DescriptorsHTML . $this->BuildHTML('descriptorsform', $ProcessedDescriptors);
        $DeCSHTML = $this->BuildHTML('decsform', $ProcessedDeCS);
        $DTO->SaveToModel(get_class($this), ['DescriptorsHTML' => $DescriptorsHTML, 'DeCSHTML' => $DeCSHTML]);
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

    private function AddToContent(string $TermTitle = null, array &$UsedTrees, array &$ProcessedDescriptors, &$ProcessedDeCS, array $TreeObject, string $tree_id, array $langArr, string $keyword)
    {

        if (!(isset($TermTitle)) ?: !(strlen($TermTitle) > 0)) {
            return;
        }
        if (in_array($tree_id, $UsedTrees)) {
            return;
        }
        array_push($UsedTrees, $tree_id);

        if (!(array_key_exists($keyword, $ProcessedDescriptors))) {
            $ProcessedDescriptors[$keyword] = [];
        }
        array_push($ProcessedDescriptors[$keyword], ['title'=>$TermTitle,'value'=>$TermTitle,'checked'=>true]);
        $tmp = [];
        foreach ($TreeObject as $lang => $content) {
            if ($lang==='descendants' || (!(in_array($lang, $langArr)))) {
                continue;
            }
            $term = $content['term'];
            $decs = $content['decs'];
            $tmp = array_merge($tmp, [$term], $decs);
        }
        $DeCSArr = [];
        foreach ($tmp as $DeCS) {
            array_push($DeCSArr, ['title' => $DeCS, 'value' =>$DeCS,'checked'=>true]);
        }
        $ProcessedDeCS[$TermTitle . ' ['. $keyword.']'] = $DeCSArr;
    }

    private function getTermsArr(array $tree)
    {
        $results = [];
        foreach ($tree['tree'] as $lang => $content) {
            if ($lang !== 'descendants') {
                $results[$lang] = $content['term'];
            }
        }
        return $results;
    }


    private function CompareDescriptors(string $lang, array $tree1, array $tree2 = null)
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

    private function IsEmptyTermsArr(array $TermsArr = null)
    {
        if (!(isset($TermsArr)) ?: !(is_array($TermsArr)) ?: count($TermsArr) == 0) {
            return true;
        } else {
            return false;
        }
    }

}
