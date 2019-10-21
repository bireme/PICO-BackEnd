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

    private $Exclude = ['(', ')', ' ', 'OR', 'AND', 'NOT', ':'];

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
        $keytypes = ['keypartial', 'keyword', 'keyrep', 'keyexplored'];

        $QuerySplit = $this->PutKeyWordEquationsInQuerySplit($QuerySplit, $keytypes, $SelectedDescriptors, $ImproveSearchWords);

        $newQuery = $this->JoinIntoNewEquation($QuerySplit);
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

    private function PutKeyWordEquationsInQuerySplit(array $QuerySplit, array $keytypes, array $SelectedDescriptors, array $ImproveSearchWords)
    {

        $selectedwordlist = [];
        foreach ($SelectedDescriptors as $keyword => $keywordData) {
            foreach ($keywordData as $Term => $DeCSArr) {
                if (count($DeCSArr)) {
                    $selectedwordlist = array_merge($selectedwordlist, $DeCSArr);
                    array_push($selectedwordlist, $Term);
                }
            }
        }

        $selectedwordlist = array_unique($selectedwordlist);
        $availableList = [];

        $QuerySplit = $this->RemoveDeCSNotFound($QuerySplit, $ImproveSearchWords, $keytypes, $selectedwordlist, $availableList);

        $NewSelectedDescriptors=null;
        if(count($availableList)){
            $NewSelectedDescriptors = $this->BuildNewSelectedDescriptors($SelectedDescriptors, $availableList);
        }

        $QuerySplit = $this->ReBuildQuerySplit($QuerySplit, $keytypes, $SelectedDescriptors, $NewSelectedDescriptors);

        return $QuerySplit;
    }

    private function RemoveDeCSNotFound(array $QuerySplit, array $ImproveSearchWords, array $keytypes, array $selectedwordlist, array &$availableList)
    {
        $RepeatMaster = [];
        foreach ($QuerySplit as $index => $itemArray) {
            $type = $itemArray['type'];
            $value = strtolower($itemArray['value']);
            if ($type === 'op') {
                $QuerySplit[$index]['value'] = strtoupper($value);
            } else {
                $value = ucwords($value);
                if ($type === 'improve') {
                    if (!(in_array($value, $ImproveSearchWords))) {
                        $QuerySplit[$index]['value'] = '';
                    }
                } elseif ($type !== 'sep') {
                    if (in_array($type, $keytypes)) {
                        if (in_array($value, $selectedwordlist)) {
                            if (!(in_array($value, array_keys($RepeatMaster)))) {
                                $RepeatMaster[$value] = false;
                            }
                            if (($RepeatMaster[$value] === false)) {
                                $RepeatMaster[$value] = true;
                            } else {
                                $QuerySplit[$index]['type'] = 'decs';
                            }
                        }

                    }
                    if ($type === 'decs') {
                        if (!(in_array($value, $selectedwordlist))) {
                            $QuerySplit[$index]['value'] = '';
                        } else {
                            array_push($availableList, $value);
                        }
                    } elseif ($type === 'term') {
                        if (!(in_array($value, $selectedwordlist))) {
                            $QuerySplit[$index]['value'] = '';
                        } else {
                            array_push($availableList, $value);
                        }
                    }
                }
            }
        }
        return $QuerySplit;
    }


    private function BuildNewSelectedDescriptors(array $SelectedDescriptors, array $availableList)
    {
        $NewSelectedDescriptors = [];
        foreach ($SelectedDescriptors as $keyword => $keywordData) {
            $keyword = ucwords(strtolower($keyword));
            $localterm = null;
            $localterm = [];
            foreach ($keywordData as $Term => $DeCSArr) {
                $localdecs = array_diff($DeCSArr, $availableList);
                if (count($localdecs)) {
                    if (in_array($Term, $availableList)) {
                        $localterm['dont' . $Term] = $localdecs;
                    } else {
                        $localterm[$Term] = $localdecs;
                    }
                }
            }
            if (count($localterm)) {
                $NewSelectedDescriptors[$keyword] = $localterm;
            } else {
                $NewSelectedDescriptors[$keyword] = -1;
            }
        }
        return $NewSelectedDescriptors;
    }

    private function ReBuildQuerySplit(array $QuerySplit, array $keytypes, array $SelectedDescriptors, array $NewSelectedDescriptors=null)
    {
        $KeywordEquations = [];
        foreach ($QuerySplit as $index => $itemArray) {
            $type = $itemArray['type'];
            $changed=false;
            $value = strtolower($itemArray['value'] ?? '');
            if (strlen($value) === 0) {
                continue;
            }
            $value = ucwords(strtolower($value));
            if (in_array($type, $keytypes)) {
                $content = $KeywordEquations[$value] ?? null;
                if ($content === null) {
                    $DescriptorsToProcess = $NewSelectedDescriptors[$value]??$SelectedDescriptors[$value];
                    if ($DescriptorsToProcess !== -1) {
                        $content = $this->BuildKeywordEquation($value, $DescriptorsToProcess);
                    } else {
                        $content = '';
                    }
                    $KeywordEquations[$value] = $content;
                }
                if (strlen($content)>0) {
                    $QuerySplit[$index]['value'] = $content;
                    $changed=true;
                }
            }
            if ($type!=='op' && $type!="sep" && $changed===false) {
                $value = $this->AddQuotesIfNecesary($value);
                $QuerySplit[$index]['value'] = $value;
            }
        }
        return $QuerySplit;
    }


    private function BuildKeywordEquation(string $keyword, array $KeywordData)
    {
        $TermEquation = '';
        $DontAdd = [];

        array_push($DontAdd, ucwords(strtolower($keyword)));
        foreach ($KeywordData as $term => $DeCSArr) {
            if (count($DeCSArr) === 0) {
                continue;
            }
            $equation = '';
            $AddTerm = true;
            if (substr($term, 0, 4) === 'dont') {
                $AddTerm = false;
            }
            if ($AddTerm === true) {
                $term = ucwords(strtolower($term));
                if (!(in_array($term, $DontAdd))) {
                    array_push($DontAdd, $term);
                    if (strpos($term, ' ') !== false) {
                        $term = '"' . $term . '"';
                    }
                    $equation = $term;
                }
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
            $TermEquation = $TermEquation . ' OR ' . $equation;
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
        $DeCSArr = array_map('ucwords', $DeCSArr);
        $DeCSArr = array_diff($DeCSArr, $DontAdd);
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
