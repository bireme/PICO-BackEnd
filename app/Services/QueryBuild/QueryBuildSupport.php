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

    private $Exclude = ['(', ')', ' ', 'OR', 'AND', 'NOT'];

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

    protected function getImproveEquation(DataTransferObject $DTO, string $ImproveSearchQuery)
    {
        if (strlen($ImproveSearchQuery)) {
            $ImproveSearchSplit = $this->ProcessQuery($ImproveSearchQuery);
            $ImproveSearchWords = array_diff($ImproveSearchSplit, $this->Exclude);
            $ImproveSearchWords = array_map('strtolower', $ImproveSearchWords);
            $ImproveSearchWords = array_map('ucwords', $ImproveSearchWords);
        } else {
            $ImproveSearchSplit = [];
            $ImproveSearchWords = [];
        }
        $DTO->SaveToModel(get_class($this), ['ImproveSearchSplit' => $ImproveSearchSplit, 'ImproveSearchWords' => $ImproveSearchWords]);
    }

    protected function BuildnewEquation(DataTransferObject $DTO, array $SelectedDescriptors)
    {
        $QuerySplit = $DTO->getAttr('QuerySplit');
        $ImproveSearchWords = $DTO->getAttr('ImproveSearchWords');
        $keytypes = ['keypartial', 'keyword', 'keyrep'];

        $KeywordsDeCSTerms = $this->getDeCSAndKeywords($SelectedDescriptors);

        $QuerySplit = $this->PutKeyWordEquationsInQuerySplit($QuerySplit, $keytypes, $SelectedDescriptors, $KeywordsDeCSTerms, $ImproveSearchWords);
        $newQuery = $this->JoinIntoNewEquation($QuerySplit);

        if (strlen($newQuery)) {
            $newQuery = '(' . $newQuery . ')';
        }
        $newQuery = $this->ImproveBasicEquation($DTO, $newQuery);
        $newQuery = $this->FixEquation($newQuery);
        $DTO->SaveToModel(get_class($this), ['newQuery' => $newQuery]);
    }

    /////////////////////////////////////
    /// INNER
    /// ///////////////////////////

    private function ImproveBasicEquation(DataTransferObject $DTO, String $newQuery)
    {
        $ImproveSearchWords = $DTO->getAttr('ImproveSearchWords');
        if (count($ImproveSearchWords) === 0) {
            $ImproveSearchQuery = '';
        } else {
            $ImproveSearchSplit = $DTO->getAttr('ImproveSearchSplit');
            $ImproveSearchQuery = join('', $ImproveSearchSplit);
            if (strlen($ImproveSearchQuery)) {
                $newQuery = $newQuery . ' OR (' . $ImproveSearchQuery . ')';
            }
        }
        $DTO->SaveToModel(get_class($this), ['ImproveSearchQuery' => $ImproveSearchQuery]);
        return $newQuery;
    }

    private function JoinIntoNewEquation(array $QuerySplit)
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

    private function PutKeyWordEquationsInQuerySplit(array $QuerySplit, array $keytypes, array $SelectedDescriptors, array $KeywordsDeCSTerms, array $ImproveSearchWords)
    {
        $KeywordEquations = [];
        foreach ($QuerySplit as $index => $itemArray) {
            $type = $itemArray['type'];
            $value = strtolower($itemArray['value']);
            $changed = false;
            if ($type === 'op') {
                $QuerySplit[$index]['value'] = strtoupper($value);
            } else {
                if ($type === 'improve') {
                    if (!(in_array($value, $ImproveSearchWords))) {
                        $QuerySplit[$index]['value'] = '';
                    }
                } elseif ($type !== 'sep') {
                    $value = ucwords(strtolower($value));
                    if (in_array($type, $keytypes)) {
                        $content = $KeywordEquations[$value]??null;
                        if ($content === null) {
                            $content = $this->BuildKeywordEquation($value, $SelectedDescriptors);
                            $KeywordEquations[$value] = $content;
                        }
                        $QuerySplit[$index]['value'] = $content;
                        $changed = true;
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

    private function BuildKeywordEquation(string $keyword, array $SelectedDescriptors)
    {
        $KeywordData=$SelectedDescriptors[ucwords(strtolower($keyword))];
        $TermEquation = '';
        $DontAdd = [];
        array_push($DontAdd, strtolower($keyword));
        foreach ($KeywordData as $term => $DeCSArr) {
            if (count($DeCSArr) === 0) {
                continue;
            }
            $term = strtolower($term);
            if (!(in_array($term, $DontAdd))) {
                array_push($DontAdd, $term);
                $localterm = ucwords(strtolower($term));
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
            $keyword = ucwords(strtolower($keyword));
            if (strpos($keyword, ' ') !== false) {
                $keyword = '"' . $keyword . '"';
            }
            $TermEquation = '(' . $keyword . $TermEquation . ')';
        }
        return $TermEquation;
    }

    private function BuildDeCSEquation(array $DeCSArr, array $DontAdd)
    {

        $DeCSArr = array_map('strtolower', $DeCSArr);
        $DeCSArr = array_diff($DeCSArr, $DontAdd);
        $DeCSArr = array_map('ucwords', $DeCSArr);
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

    private function getDeCSAndKeywords(array $Data = null)
    {
        $Keywords = [];
        $DeCS = [];
        $terms = [];
        if ($Data && is_array($Data)) {
            foreach ($Data as $keyword => $KeywordData) {
                array_push($Keywords, $keyword);
                foreach ($KeywordData as $Term => $TermData) {
                    array_push($terms, $Term);
                    $DeCS = array_merge($DeCS, $TermData);
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
