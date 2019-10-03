<?php

namespace PICOExplorer\Services\DeCS;

use PICOExplorer\Exceptions\Exceptions\ClientError\NoNewDataToExplore;
use PICOExplorer\Exceptions\Exceptions\ClientError\WrongQueryFormat;
use PICOExplorer\Facades\UltraLoggerFacade;
use PICOExplorer\Models\DataTransferObject;
use PICOExplorer\Services\ServiceModels\PICOQueryProcessorTrait;
use PICOExplorer\Services\AdvancedLogger\Services\UltraLoggerDevice;

abstract class DeCSQueryBuild extends DeCSSupport
{

    use PICOQueryProcessorTrait;

    protected function ProcessInitialQuery(string $query, string $ImprovedSearch = null)
    {
        $QueryProcessed=[];
        $ImprovedSearchProcessed=[];
        if ($ImprovedSearch) {
            $ImprovedSearchProcessed = $this->ProcessQuery($ImprovedSearch);
        }
        if($query){
            $QueryProcessed = $this->ProcessQuery($query);
        }
        $infodata = [
            'ImprovedSearchProcessed' => $ImprovedSearchProcessed,
            'QueryProcessed' => $QueryProcessed,
        ];
        return $infodata;
    }


    protected function BuildListsFromProcessedData(DataTransferObject $DTO, array $infodata, array $langArr, UltraLoggerDevice $Log)
    {
        $ItemArray = $infodata['QueryProcessed'];
        $ImprovedArray = $infodata['ImprovedSearchProcessed'];
        $Ops = ['or', 'and', 'not'];
        $Seps = ['(', ')', ' ', ':'];
        try {
            $infoimprov = $this->BuildImproveList($ImprovedArray, $Ops, $Seps);
            $infodata = $this->BuildKeywordList($DTO, $ItemArray, $langArr, $Ops, $Seps, $infoimprov, $Log);
            $KeywordList = $infodata['KeywordList'];
            $AllKeywords =$infodata['AllKeywords']??[];
            $QuerySplit = $this->improveQuerySplit($infodata['QuerySplit'], $Ops, $Seps);
        } catch (\Throwable $ex) {
            throw new WrongQueryFormat();
        }
        if (count($KeywordList ?? []) === 0) {
            throw new NoNewDataToExplore(['TreesToExplore' => null]);
        }

        UltraLoggerFacade::MapArrayIntoUltraLogger($Log, 'KeywordList', ['KeywordList' => $KeywordList], 3);
        UltraLoggerFacade::MapArrayIntoUltraLogger($Log, 'QuerySplit', ['QuerySplit' => $QuerySplit], 1, 'value');
        $DTO->SaveToModel(get_class($this), ['QuerySplit' => $QuerySplit, 'KeywordList' => $KeywordList,'AllKeywords'=>$AllKeywords]);

    }

///////////////////////////////////////////////////////////////////
//INNER FUNCTIONS
///////////////////////////////////////////////////////////////////

    private function BuildImproveList(array $ItemArray, array $Ops, array $Seps)
    {
        $exclude = array_merge($Ops, $Seps);
        $ImproveWords = [];
        foreach ($ItemArray as $index => $value) {
            if (!(in_array($value, $exclude))) {
                array_push($ImproveWords, $value);
            }
        }
        return $ImproveWords;
    }

    private function BuildKeywordList(DataTransferObject $DTO, array $ItemArray, array $langArr, array $Ops, array $Seps, array $infoimprov, UltraLoggerDevice $Log)
    {
        $level = 0;
        $PreviousData = $DTO->getAttr('PreviousData');
        $ItemArray = array_map('strtolower', $ItemArray);
        $KeywordsDeCSTerms = $this->getDeCSAndKeywords($PreviousData);
        $UsedKeyWords = [];
        $KeywordList = [];
        $QuerySplit = [];
        $localkeywords = [];
        $AllKeywords=[];
        $localkeywords[0] = [];
        array_push($localkeywords[0], []);
        foreach ($ItemArray as $index => $value) {
            $type = null;
            if (strlen($value) === 0) {
                continue;
            }
            $value = str_replace('"', '', $value);
            $value = str_replace("'", '', $value);
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
                $type = $this->WordType($value, $KeywordsDeCSTerms, $UsedKeyWords, $Seps, $Ops, $infoimprov);
                if ($type !== 'decs' && $type !== 'term' && $type !== 'improve' ) {
                    if ($type === 'keyexplored' || $type === 'keyword' || $type === 'keyrep') {
                        array_push($AllKeywords,$value);
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
            }
            array_push($QuerySplit, ['type' => $type, 'value' => $value]);
        }
        $infodata = ['QuerySplit' => $QuerySplit, 'KeywordList' => $KeywordList,'AllKeywords'=>$AllKeywords];
        return $infodata;
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
            $localLangs = array_diff(array_keys($treedata), ['descendants', 'remaininglangs']);
            $tmpUnexplored = array_diff($langArr, $localLangs);
            array_merge($Unexplored, $tmpUnexplored);
        }
        return $Unexplored;
    }


    private
    function WordType(string $word, array $KeywordsDeCSTerms, array $UsedKeyWords, array $Ops, array $Seps, array $infoimprov)
    {
        if (in_array($word, $Seps)) {
            return 'sep';
        }
        if (in_array($word, $Ops)) {
            return 'op';
        }
        if (in_array($word, $infoimprov)) {
            return 'improve';
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
