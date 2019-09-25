<?php

namespace PICOExplorer\Services\DeCS;

use PICOExplorer\Exceptions\Exceptions\AppError\PreviousDataCouldNotBeDecoded;
use PICOExplorer\Exceptions\Exceptions\ClientError\NoNewDataToExplore;
use PICOExplorer\Models\DataTransferObject;
use PICOExplorer\Services\ServiceModels\PICOQueryProcessorTrait;
use PICOExplorer\Services\ServiceModels\ServiceEntryPoint;
use PICOExplorer\Services\ServiceModules\BuildHTMLTrait;
use Throwable;

abstract class DeCSInfoProcessor extends ServiceEntryPoint
{

    use BuildHTMLTrait;
    use PICOQueryProcessorTrait;

    protected function DecodePreviousData(DataTransferObject $DTO, string $undecodedPreviousData = null)
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
            } else {
                foreach ($keywordData as $lang => $content) {
                    if (!(array_key_exists($lang, $SavedData[$keyword]))) {
                        $SavedData[$keyword][$lang] = $content;
                    } else {
                        if ($lang !== 'descendants') {
                            if (!(array_key_exists('term', $SavedData[$keyword][$lang]))) {
                                $SavedData[$keyword][$lang]['term'] = [];
                                $SavedData[$keyword][$lang]['decs'] = [];
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
        $QuerySplit = $DTO->getAttr('QuerySplit');
        $DescriptorsHTML = $this->BuildHiddenField('descriptorsform', 'querysplit', $QuerySplit);
        $DescriptorsHTML = $DescriptorsHTML . $this->BuildHiddenField('descriptorsform', 'piconum', $PICOnum);
        $DescriptorsHTML = $DescriptorsHTML . $this->BuildHTML('descriptorsform', $ProcessedDescriptors);
        $DeCSHTML = $this->BuildHTML('decsform', $ProcessedDeCS);
        $DTO->SaveToModel(get_class($this), ['DescriptorsHTML' => $DescriptorsHTML, 'DeCSHTML' => $DeCSHTML]);
    }


    protected function ProcessInitialQuery(string $query, string $ImprovedSearch = null)
    {
        if ($ImprovedSearch) {
            $searchlen = strlen($ImprovedSearch) + 10;
            $queryini = substr($query, 0, (strlen($query) - $searchlen));
            $queryfinal = substr($query, -($searchlen));
            $queryfinal = str_replace($ImprovedSearch, '', $queryfinal);
            $query = $queryini . $queryfinal;
        }
        $QueryProcessed = $this->ProcessQuery($query);
        return $QueryProcessed;
    }

    protected function BuildKeywordList(DataTransferObject $DTO, array $QueryProcessed, array $langArr)
    {
        $PreviousData = $DTO->getAttr('PreviousData');
        $KeywordListAndQuerySplit = $this->setArrayItemType($QueryProcessed, $PreviousData, $langArr);
        if (count($KeywordListAndQuerySplit['KeywordList'] ?? []) === 0) {
            throw new NoNewDataToExplore(['TreesToExplore' => null]);
        }
        $DTO->SaveToModel(get_class($this), $KeywordListAndQuerySplit);
    }

///////////////////////////////////////////////////////////////////
//INNER FUNCTIONS
///////////////////////////////////////////////////////////////////


    private function setArrayItemType(array $ItemArray, array $PreviousData, array $langArr)
    {
        $ItemArray = array_map('strtolower', $ItemArray);
        $KeywordsDeCSTerms = $this->getDeCSAndKeywords($PreviousData);
        $Ops = ['or', 'and', 'not'];
        $Seps = ['(', ')', ' ', ':'];
        $UsedKeyWords = [];
        $KeywordList = [];
        $QuerySplit = [];
        $localkeywords = [];
        $localkeywords[0] = [];
        array_push($localkeywords[0], []);
        $level = 0;
        foreach ($ItemArray as $index => $value) {
            $type = null;
            if (strlen($value) === 0) {
                continue;
            }
            if ($value === '(') {
                $type = 'op';
                $level++;
                if (!(array_key_exists($level, $localkeywords))) {
                    $localkeywords[$level] = [];
                }
                array_push($localkeywords[$level], []);
            } elseif ($value === ')') {
                $level--;
                $type = 'op';
            } else {
                $type = $this->WordType($value, $KeywordsDeCSTerms, $UsedKeyWords, $Seps, $Ops);
                if ($type === 'decs' || $type === 'term') {
                    continue;
                }
                if ($type === 'keyexplored' || $type === 'keyword' || $type === 'keyrep') {
                    $kwindex = count($localkeywords[$level]) - 1;
                    if (in_array($value, $localkeywords[$level][$kwindex])) {
                        continue;
                    } else {
                        array_push($localkeywords[$level][$kwindex], $value);
                    }
                    if ($type === 'keyexplored' || $type === 'keyword') {
                        $UnexploredLangs = $this->getUnexploredLangs($value, $PreviousData, $langArr);
                        if ($type === 'keyexplored') {
                            if (count($UnexploredLangs) > 0) {
                                $type = 'keypartial';
                            }
                        }
                        array_push($UsedKeyWords, $value);
                        $KeywordList[$value] = $UnexploredLangs;
                    }
                }
            }
            array_push($QuerySplit, ['type' => $type, 'value' => $value]);
        }
        $QuerySplit = $this->improveQuerySplit($QuerySplit, $Ops, $Seps);
        $res = ['QuerySplit' => $QuerySplit, 'KeywordList' => $KeywordList];
        return $res;
    }

    private function improveQuerySplit(array $QuerySplit, array $Ops, array $Seps)
    {

        $error = true;
        $previous = null;
        $previousTwo = null;
        while ($error) {
            $error = false;
            $totaldeleteindexes = [];
            foreach ($QuerySplit as $index => $item) {
                if (!($item)) {
                    continue;
                }
                $type = $QuerySplit[$index]['type'];
                $deleteindexes = null;

                if ($previous) {
                    if (!($type === 'keyexplored' || $type === 'keyword' || $type === 'keyrep' || $type === 'keypartial')) {
                        $value = $QuerySplit[$index]['value'] ?? '';
                        $previousValue = $QuerySplit[$previous]['value'] ?? '';
                        $previousTwoValue = null;
                        if ($previousTwo) {
                            $previousTwoValue = $QuerySplit[$previousTwo]['value'] ?? null;
                        }
                        $deleteindexes = $this->CorrectErrors($Ops, $index, $previous, $value, $previousValue, $previousTwo, $previousTwoValue);
                    }
                }
                if ($deleteindexes) {
                    $totaldeleteindexes = array_merge($totaldeleteindexes, $deleteindexes);
                    $previousTwo = null;
                    $previous = null;
                    $error = true;
                } else {
                    if ($previous) {
                        $previousTwo = $previous;
                    }
                    $previous = $index;
                }
            }
            $this->deleteErrors($QuerySplit, $totaldeleteindexes);
            $show = [];
            $this->reBuildWithoutNulls($QuerySplit, $show);
            $this->removeBorders($QuerySplit, $Ops, $error);
        }
        return $QuerySplit;
    }

    private function CorrectErrors(array $Ops, int $index, int $previous, string $value, string $previousValue, int $previousTwo = null, string $previousTwoValue = null)
    {
        if ($previousTwo) {
            if ((in_array($value, $Ops)) && (in_array($previousTwoValue, $Ops)) && ($previousValue === ' ') && ($previousTwoValue !== 'not')) {
                return [$previousTwo, $previous, $index];
            }
            if ($previousTwoValue === '(' && $value === ')') {
                return [$previousTwo, $index];
            }
            if ($previousTwoValue === '(' && $previousValue === ' ' && in_array($value, $Ops) && $value !== 'not') {
                return [$previous, $index];
            }
            if (in_array($previousTwoValue, $Ops) && $previousValue === ' ' && $value === ')') {
                return [$previousTwo, $previous];
            }

            if ($previousTwoValue === '(' && $value === ' ' && in_array($previousValue, $Ops) && $previousValue !== 'not') {
                return [$previous, $index];
            }
            if (in_array($previousValue, $Ops) && $previousTwoValue === ' ' && $value === ')') {
                return [$previousTwo, $previous];
            }
        }
        if ($previousValue === ' ' && $value === ' ') {
            return [$previous];
        }
        if ($previousValue === ' ' && $value === ')') {
            return [$previous];
        }
        if ($previousValue === '(' && $value === ' ') {
            return [$index];
        }
        if ($previousValue === '(' && $value === ')') {
            return [$previous, $index];
        }
        return null;
    }

    private function deleteErrors(array &$QuerySplit, array $totaldeleteindexes)
    {
        if (count($totaldeleteindexes) > 0) {
            rsort($totaldeleteindexes);

            foreach ($totaldeleteindexes as $itemdelete) {
                unset($QuerySplit[$itemdelete]);
            }
        }
    }

    private function reBuildWithoutNulls(array &$QuerySplit, array &$show)
    {
        $newQuerySplit = [];
        foreach ($QuerySplit as $index => $item) {
            if ($item) {
                array_push($show, $item['value']);
                array_push($newQuerySplit, $item);
            }
        }
        $QuerySplit = $newQuerySplit;
    }

    private function removeBorders(array &$QuerySplit, array $Ops, bool &$error)
    {
        $tmperror = true;
        while ($tmperror) {
            $tmperror = false;
            $firstval = $QuerySplit[0]['value'];
            $lastval = $QuerySplit[count($QuerySplit)-1]['value'];
            if (in_array($lastval, $Ops) || $lastval === ' ') {
                array_pop($QuerySplit);
                $error = true;
                $tmperror = true;
            }
            if (in_array($firstval, $Ops) || $firstval === ' ') {
                array_shift($QuerySplit);
                $error = true;
                $tmperror = true;
            }
        }
    }

    private function getUnexploredLangs(string $keyword, array $PreviousData, array $langArr)
    {
        $KeywordData = $PreviousData[$keyword] ?? null;
        if (!($KeywordData)) {
            return $langArr;
        }
        $Unexplored = [];
        foreach ($KeywordData as $tree_id => $treedata) {
            $localLangs = array_diff(array_keys($treedata), ['descendants']);
            $tmpUnexplored = array_diff($langArr, $localLangs);
            array_merge($Unexplored, $tmpUnexplored);
        }
        return $Unexplored;
    }


    private
    function WordType(string $word, array $KeywordsDeCSTerms, array $UsedKeyWords, array $Ops, array $Seps)
    {
        if (in_array($word, $Seps)) {
            return 'sep';
        }
        if (in_array($word, $Ops)) {
            return 'op';
        }
        if (in_array($word, array_values($KeywordsDeCSTerms['decs']))) {
            return 'decs';
        }
        if (in_array($word, array_values($KeywordsDeCSTerms['term']))) {
            return 'term';
        }
        if (in_array($word, $UsedKeyWords)) {
            return 'keyrep';
        }
        if (in_array($word, array_values($KeywordsDeCSTerms['keyword']))) {
            return 'keyexplored';
        }
        return 'keyword';
    }

    private
    function getDeCSAndKeywords(array $PreviousData = null)
    {
        $Keywords = [];
        $DeCS = [];
        $terms = [];
        if ($PreviousData && is_array($PreviousData)) {
            foreach ($PreviousData as $keyword => $KeywordData) {
                array_push($Keywords, $keyword);
                foreach ($KeywordData as $tree_id => $TreeData) {
                    foreach ($TreeData as $lang => $langData) {
                        if ($lang !== 'descendants') {
                            $DeCS = array_merge($DeCS, $langData['decs']);
                            array_push($terms, $langData['term']);
                        }
                    }
                }
            }
            $Keywords = array_map('strtolower', $Keywords);
            $DeCS = array_map('strtolower', $DeCS);
            $terms = array_map('strtolower', $terms);
            $Keywords = array_unique($Keywords);
            $DeCS = array_unique($DeCS);
            $terms = array_unique($terms);
            $DeCS = array_diff($DeCS, $terms);
            $DeCS = array_diff($DeCS, $Keywords);
            $terms = array_diff($terms, $Keywords);
        }
        $KeywordsDeCSTerms = [
            'keyword' => $Keywords,
            'decs' => $DeCS,
            'term' => $terms,
        ];
        return $KeywordsDeCSTerms;
    }

    private
    function getCompLang(string $mainlanguage, array $langArr)
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

    private
    function AddToContent(string $TermTitle = null, array &$UsedTrees, array &$ProcessedDescriptors, &$ProcessedDeCS, array $TreeObject, string $tree_id, array $langArr, string $keyword)
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
        array_push($ProcessedDescriptors[$keyword], ['title' => $TermTitle, 'value' => $TermTitle, 'checked' => true]);
        $tmp = [];
        foreach ($TreeObject as $lang => $content) {
            if ($lang === 'descendants' || (!(in_array($lang, $langArr)))) {
                continue;
            }
            $term = $content['term'];
            $decs = $content['decs'];
            $tmp = array_merge($tmp, [$term], $decs);
        }
        $DeCSArr = [];
        foreach ($tmp as $DeCS) {
            array_push($DeCSArr, ['title' => $DeCS, 'value' => $DeCS, 'checked' => true]);
        }
        $ProcessedDeCS[$TermTitle . ' [' . $keyword . ']'] = $DeCSArr;
    }

    private
    function getTermsArr(array $tree)
    {
        $results = [];
        foreach ($tree['tree'] as $lang => $content) {
            if ($lang !== 'descendants') {
                $results[$lang] = $content['term'];
            }
        }
        return $results;
    }


    private
    function CompareDescriptors(string $lang, array $tree1, array $tree2 = null)
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

    private
    function IsEmptyTermsArr(array $TermsArr = null)
    {
        if (!(isset($TermsArr)) ?: !(is_array($TermsArr)) ?: count($TermsArr) == 0) {
            return true;
        } else {
            return false;
        }
    }

}
