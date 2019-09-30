<?php

namespace PICOExplorer\Services\DeCS;

use PICOExplorer\Exceptions\Exceptions\ClientError\NoNewDataToExplore;
use PICOExplorer\Facades\UltraLoggerFacade;
use PICOExplorer\Models\DataTransferObject;
use PICOExplorer\Services\ServiceModels\PICOQueryProcessorTrait;
use PICOExplorer\Services\AdvancedLogger\Services\UltraLoggerDevice;

abstract class DeCSQueryBuild extends DeCSInfoProcessor
{

    use PICOQueryProcessorTrait;

    protected function ProcessInitialQuery(string $query, string $ImprovedSearch = null)
    {
        if ($ImprovedSearch && strlen($ImprovedSearch)) {
            $lastpos = strpos($ImprovedSearch, $query);
            if($lastpos){
                $queryini = substr($query, 0, $lastpos-1);
                $queryfinal = substr($query, -(strlen($query)-$lastpos));
                $queryfinal = str_replace($ImprovedSearch, '', $queryfinal);
                $query = $queryini . $queryfinal;
            }
        }
        $QueryProcessed = $this->ProcessQuery($query);
        return $QueryProcessed;
    }

    protected function BuildKeywordList(DataTransferObject $DTO, array $ItemArray, array $langArr,UltraLoggerDevice $Log)
    {
        $PreviousData = $DTO->getAttr('PreviousData');
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
        UltraLoggerFacade::MapArrayIntoUltraLogger($Log, 'KeywordList', ['KeywordList' => $KeywordList], 3);
        $QuerySplit = $this->improveQuerySplit($QuerySplit, $Ops, $Seps);
        UltraLoggerFacade::MapArrayIntoUltraLogger($Log, 'QuerySplit', ['QuerySplit' => $QuerySplit], 1, 'value');

        if (count($KeywordList ?? []) === 0) {
            throw new NoNewDataToExplore(['TreesToExplore' => null]);
        }
        $DTO->SaveToModel(get_class($this), ['QuerySplit' => $QuerySplit, 'KeywordList' => $KeywordList]);
    }

///////////////////////////////////////////////////////////////////
//INNER FUNCTIONS
///////////////////////////////////////////////////////////////////


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
            $lastval = $QuerySplit[count($QuerySplit) - 1]['value'];
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
            $localLangs = array_diff(array_keys($treedata), ['descendants','remaininglangs']);
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
                        if (!($lang === 'descendants' || $lang === 'remaininglangs')) {
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

}
