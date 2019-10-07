<?php

namespace PICOExplorer\Services\DeCS;

use Illuminate\Support\Facades\Lang;
use PICOExplorer\Exceptions\Exceptions\ClientError\EmptyQuery;
use PICOExplorer\Exceptions\Exceptions\ClientError\NoNewDataToExplore;
use PICOExplorer\Exceptions\Exceptions\ClientError\WrongQueryFormat;
use PICOExplorer\Facades\UltraLoggerFacade;
use PICOExplorer\Http\Traits\CorrectQueryTrait;
use PICOExplorer\Models\DataTransferObject;
use PICOExplorer\Services\ServiceModels\PICOQueryProcessorTrait;
use PICOExplorer\Services\AdvancedLogger\Services\UltraLoggerDevice;

abstract class DeCSQueryBuild extends DeCSSupport
{

    use PICOQueryProcessorTrait;
    use CorrectQueryTrait;

    protected function ProcessInitialQuery(string $query)
    {
        if ($query && strlen($query)) {
            $query = $this->FixEquation($query);
            $QueryProcessed = $this->ProcessQuery($query);
            return $QueryProcessed;
        } else {
            throw new EmptyQuery();
        }
    }


    protected function BuildListsFromProcessedData(DataTransferObject $DTO, array $ItemArray, array $langArr, UltraLoggerDevice $Log)
    {
        $ImproveSearchWords = $DTO->getAttr('ImproveSearchWords');
        $Ops = ['or', 'and', 'not'];
        $Seps = ['(', ')', ' ', ':'];
        $arrayErrorMessageData = null;
        try {
            $infodata = $this->BuildKeywordList($DTO, $ItemArray, $langArr, $Ops, $Seps, $ImproveSearchWords, $Log);
            $KeywordList = $infodata['KeywordList'];
            $AllKeywords = $infodata['AllKeywords'] ?? [];
            $QuerySplit = $infodata['QuerySplit'];
        } catch (\Throwable $ex) {
            throw new WrongQueryFormat();
        }
        if (count($KeywordList ?? []) === 0) {
            throw new NoNewDataToExplore(['TreesToExplore' => null]);
        }

        UltraLoggerFacade::MapArrayIntoUltraLogger($Log, 'KeywordList', ['KeywordList' => $KeywordList], 3);
        UltraLoggerFacade::MapArrayIntoUltraLogger($Log, 'QuerySplit', ['QuerySplit' => $QuerySplit], 1, 'value');
        $DTO->SaveToModel(get_class($this), ['QuerySplit' => $QuerySplit, 'KeywordList' => $KeywordList, 'AllKeywords' => $AllKeywords]);
        if(count($KeywordList)>5){
            $arrayErrorMessageData=[];
            $txtTooMuch=Lang::get('lang.KeyX');
            $arrayErrorMessageData[$txtTooMuch] = [];
            foreach ($KeywordList as $word =>$data) {
                $word=ucfirst($word);
                array_push($arrayErrorMessageData[$txtTooMuch], ['title' => $word, 'value' => $word, 'checked' => -1]);
            }
        }
        return $arrayErrorMessageData;
    }

///////////////////////////////////////////////////////////////////
//INNER FUNCTIONS
///////////////////////////////////////////////////////////////////

    private function BuildKeywordList(DataTransferObject $DTO, array $ItemArray, array $langArr, array $Ops, array $Seps, array $ImproveSearchWords, UltraLoggerDevice $Log)
    {
        $level = 0;
        $PreviousData = $DTO->getAttr('PreviousData');
        $ImproveSearchWords = array_map('strtolower', $ImproveSearchWords);
        $ItemArray = array_map('strtolower', $ItemArray);
        $KeywordsDeCSTerms = $this->getDeCSAndKeywords($PreviousData);
        $UsedKeyWords = [];
        $KeywordList = [];
        $QuerySplit = [];
        $localkeywords = [];
        $AllKeywords = [];
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
                $type = $this->WordType($value, $KeywordsDeCSTerms, $UsedKeyWords, $Ops, $Seps, $ImproveSearchWords);
                if ($type !== 'decs' && $type !== 'term' && $type !== 'improve') {
                    if ($type === 'keyexplored' || $type === 'keyword' || $type === 'keyrep') {
                        array_push($AllKeywords, $value);
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
            if ($type === 'op') {
                $value = strtoupper($value);
            }
            array_push($QuerySplit, ['type' => $type, 'value' => $value]);
        }
        $infodata = ['QuerySplit' => $QuerySplit, 'KeywordList' => $KeywordList, 'AllKeywords' => $AllKeywords];
        return $infodata;
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
    function WordType(string $word, array $KeywordsDeCSTerms, array $UsedKeyWords, array $Ops, array $Seps, array $ImproveSearchWords)
    {
        if (in_array($word, $Seps)) {
            return 'sep';
        }
        if (in_array($word, $Ops)) {
            return 'op';
        }
        if (in_array($word, $ImproveSearchWords)) {
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
