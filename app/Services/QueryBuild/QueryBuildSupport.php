<?php

namespace PICOExplorer\Services\QueryBuild;

use PICOExplorer\Exceptions\Exceptions\AppError\PreviousDataCouldNotBeDecoded;
use PICOExplorer\Http\Traits\CorrectQueryTrait;
use PICOExplorer\Models\DataTransferObject;
use PICOExplorer\Services\ServiceModels\PICOQueryProcessorTrait;
use PICOExplorer\Services\ServiceModels\ServiceEntryPoint;


abstract class QueryBuildSupport extends ServiceEntryPoint
{

    use PICOQueryProcessorTrait;
    use CorrectQueryTrait;

    ///////////////////////////////////////////////////////////////////
    //protected FUNCTIONS
    ///////////////////////////////////////////////////////////////////

    protected function decodeQuerySplit(DataTransferObject $DTO, string $undecodedQuerySplit = null)
    {
        $decodedQuerySplit = null;
        if ($undecodedQuerySplit) {
            try {
                $decodedQuerySplit = json_decode($undecodedQuerySplit, true);
            } catch (\Throwable $ex) {
                throw new PreviousDataCouldNotBeDecoded(['PreviousData' => json_encode($undecodedQuerySplit)], $ex);
            }
        } else {
            $decodedQuerySplit = [];
        }
        $DTO->SaveToModel(get_class($this), ['QuerySplit' => $decodedQuerySplit]);
    }

    protected function BuildBaseEquation(DataTransferObject $DTO, array $SelectedDescriptors)
    {
        $QuerySplit = $DTO->getAttr('QuerySplit');
        $keytypes = ['keyexplored', 'keypartial', 'keyword', 'keyrep'];

        $info = $this->getExcludedKeywordsAndImprove($QuerySplit, $keytypes);
        $DontAdd = $info['DontAdd'];
        $ImproveSearchWords = $info['ImproveSearchWords'];

        $KeywordEquations = $this->BuildEquationsForEachKeyword($SelectedDescriptors, $DontAdd);
        $QuerySplit = $this->PutKeyWordEquationsInQuerySplit($QuerySplit, $keytypes, $KeywordEquations);
        $newQuery = $this->BuildNewEquation($QuerySplit);
        if(strlen($newQuery)){
            $newQuery='('.$newQuery.')';
        }

        $DTO->SaveToModel(get_class($this), ['newQuery' => $newQuery, 'ImproveSearchWords' => $ImproveSearchWords]);
    }

    protected function ImproveBasicEquation(DataTransferObject $DTO, string $ImproveSearchQuery)
    {
        $newQuery = $DTO->getAttr('newQuery');
        if (strlen($ImproveSearchQuery) === 0) {
            $DTO->SaveToModel(get_class($this), ['newQuery' => $newQuery]);
        }
        $improvesplit = $this->ProcessQuery($ImproveSearchQuery);
        $ImproveSearchWords = $DTO->getAttr('ImproveSearchWords');
        $ImproveSearchWords = array_map('ucfirst', $ImproveSearchWords);
        $Exclude = ['(', ')', ' ', 'OR', 'AND', 'NOT'];
        foreach ($improvesplit as $index => $item) {
            if (!(in_array($item, $Exclude))) {
                array_push($ImproveSearchWords, ucfirst($item));
                $improvesplit[$index] = ucfirst($improvesplit[$index]);
            }
        }
        $ImproveSearchQuery = join('', $improvesplit);
        if(strlen($ImproveSearchQuery)){
            $newQuery = $newQuery . ' OR (' . $ImproveSearchQuery . ')';
        }
        $newQuery = $this->FixEquation($newQuery);
        $DTO->SaveToModel(get_class($this), ['newQuery' => $newQuery,'ImproveSearchQuery'=>$ImproveSearchQuery,'ImproveSearchWords' => $ImproveSearchWords]);
    }

    /////////////////////////////////////
    /// INNER
    /// ///////////////////////////

    private function BuildNewEquation(array $QuerySplit)
    {
        $vals = array_column($QuerySplit, 'value');
        $newQuery = join('', $vals);
        return $newQuery;
    }

    private function AddQuotesIfNecesary(String $value)
    {
        if (strpos($value, ' ') !== false) {
            $value = '"' . $value . '"';
        }
        return $value;
    }

    private function PutKeyWordEquationsInQuerySplit(array $QuerySplit, array $keytypes, array $KeywordEquations)
    {
        foreach ($QuerySplit as $index => $itemArray) {
            $type = $itemArray['type'];
            $value = strtolower($itemArray['value']);
            $changed = false;
            if ($type === 'op') {
                $QuerySplit[$index]['value'] = strtoupper($value);
            } else {
                if ($type !== 'sep') {
                    $value = ucfirst($value);
                    if (in_array($type, $keytypes)) {
                        $content = $KeywordEquations[ucfirst($value)] ?? null;
                        if ($content !== null) {
                            $QuerySplit[$index]['value'] = $content;
                            $changed = true;
                        }
                    }
                    if ($changed === false) {
                        $value = $this->AddQuotesIfNecesary($value);
                        $QuerySplit[$index]['value'] = $value;
                    }
                }
            }
        }
        return $QuerySplit;
    }

    private function getExcludedKeywordsAndImprove(array $QuerySplit, array $keytypes)
    {
        $DontAdd = [];
        $DoAdd = [];
        $dont = ['decs', 'term', 'improve'];
        $ImproveSearchWords = [];
        foreach ($QuerySplit as $itemArray) {
            $type = $itemArray['type'];
            $value = strtolower($itemArray['value']);
            if ($type === 'improve') {
                array_push($ImproveSearchWords, $value);
            }
            if (in_array($type, $dont)) {
                array_push($DontAdd, $value);
            }
            if (in_array($type, $keytypes)) {
                array_push($DoAdd, $value);
            }
        }
        $DontAdd = array_diff($DontAdd, $DoAdd);
        return ['DontAdd' => $DontAdd, 'ImproveSearchWords' => $ImproveSearchWords];
    }

    private function BuildEquationsForEachKeyword(array $SelectedDescriptors, array $DontAdd)
    {
        $KeywordEquations = [];
        foreach ($SelectedDescriptors as $keyword => $keywordData) {
            $KeywordEquations[$keyword] = $this->BuildKeywordEquation($keyword, $keywordData, $DontAdd);;
        }
        return $KeywordEquations;
    }


    private function BuildKeywordEquation(string $keyword, array $KeywordData, array $DontAdd)
    {
        $TermEquation = '';
        array_push($DontAdd, strtolower($keyword));
        foreach ($KeywordData as $term => $DeCSArr) {
            if (count($DeCSArr) === 0) {
                continue;
            }
            $term = strtolower($term);
            if (!(in_array($term, $DontAdd))) {
                array_push($DontAdd, $term);
                $localterm = ucfirst($term);
                if (strpos($localterm, ' ') !== false) {
                    $localterm = '"' . $localterm . '"';
                }
                $equation = $localterm;
            } else {
                $equation = '';
            }
            $DeCSEquation = $this->BuildDeCSEquation($DeCSArr, $DontAdd);


            if (strlen($DeCSEquation) !== 0) {
                if (strlen($equation) === 0) {
                    $equation = $DeCSEquation;
                } else {
                    $equation = $equation . ' OR ' . $DeCSEquation;
                }
            }
            if (strlen($equation) === 0) {
                continue;
            }
            $TermEquation = $TermEquation . ' OR (' . $equation . ')';
        }
        if (strlen($TermEquation)) {
            $keyword = ucfirst($keyword);
            if (strpos($keyword, ' ') !== false) {
                $keyword = '"' . $keyword . '"';
            }
            $TermEquation = '('.$keyword . $TermEquation.')';
        }
        return $TermEquation;
    }

    private function BuildDeCSEquation(array $DeCSArr, array $DontAdd)
    {

        $DeCSArr = array_map('strtolower', $DeCSArr);
        $DeCSArr = array_diff($DeCSArr, $DontAdd);
        $DeCSArr = array_map('ucfirst', $DeCSArr);
        if (count($DeCSArr)) {
            foreach ($DeCSArr as $id => $DeCS) {
                if (strpos($DeCS, ' ') !== false) {
                    $DeCSArr[$id] = '"' . $DeCS . '"';
                }
            }
            return join(' OR ', $DeCSArr);
        } else {
            return '';
        }
    }


}
