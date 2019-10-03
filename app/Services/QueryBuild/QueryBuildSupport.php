<?php

namespace PICOExplorer\Services\QueryBuild;

use PICOExplorer\Exceptions\Exceptions\AppError\PreviousDataCouldNotBeDecoded;
use PICOExplorer\Models\DataTransferObject;
use PICOExplorer\Services\ServiceModels\PICOQueryProcessorTrait;
use PICOExplorer\Services\ServiceModels\ServiceEntryPoint;


abstract class QueryBuildSupport extends ServiceEntryPoint
{

    use PICOQueryProcessorTrait;

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
        $dont = ['decs', 'term', 'improve'];

        $DontAdd = [];
        $DoAdd = [];

        foreach ($QuerySplit as $itemArray) {
            $type = $itemArray['type'];
            $value = strtolower($itemArray['value']);
            if (in_array($type, $dont)) {
                array_push($DontAdd, $value);
            }
            if (in_array($type, $keytypes)) {
                array_push($DoAdd, $value);
            }
        }

        $DontAdd = array_diff($DontAdd, $DoAdd);

        $KeywordEquations = $this->BuildKeywordEquations($SelectedDescriptors, $DontAdd);

        foreach ($QuerySplit as $index => $itemArray) {
            $type = $itemArray['type'];
            $value = strtolower($itemArray['value']);
            $changed = false;
            if (in_array($type, $keytypes)) {
                $content = $KeywordEquations[strtolower($value)] ?? null;
                if ($content !== null) {
                    $itemArray['value'] = $content;
                    $QuerySplit[$index] = $itemArray;
                    $changed = true;
                }
            }
            if ($changed === false) {
                if ($type !== 'sep' && $type !== 'op') {
                    $value = ucfirst($value);
                    if (strpos($value, ' ') !== false) {
                        $value = '"' . $value . '"';
                    }
                    $itemArray['value'] = $value;
                } elseif ($type === 'op') {
                    $itemArray['value'] = strtoupper($value);
                }
                $QuerySplit[$index] = $itemArray;
            }
        }

        $newQuery = '';
        foreach ($QuerySplit as $item) {
            $val = $item['value'] ?? null;
            if ($val !== null) {
                $newQuery = $newQuery . $val;
            }
        }

        $DTO->SaveToModel(get_class($this), ['newQuery' => $newQuery]);
    }


    protected function getImproveQuerySplit(string $ImproveSearchQuery)
    {
        $improvesplit = $this->ProcessQuery($ImproveSearchQuery);
        $improvesplit = array_map('ucfirst', $improvesplit);
        $ImproveSearchQuery = join('', $improvesplit);
        return $ImproveSearchQuery;
    }


    protected function ImproveBasicEquation(DataTransferObject $DTO, string $ImproveSearchQuery)
    {
        if (strlen($ImproveSearchQuery) === 0) {
            return;
        }
        $newQuery = $DTO->getAttr('newQuery');
        $newQuery = $newQuery . ' OR (' . $ImproveSearchQuery . ')';
        $DTO->SaveToModel(get_class($this), ['newQuery' => $newQuery]);
    }



    /////////////////////////////////////
    /// INNER
    /// ///////////////////////////

    private function BuildKeywordEquations(array $SelectedDescriptors, array $DontAdd)
    {
        $KeywordEquations = [];
        foreach ($SelectedDescriptors as $keyword => $keywordData) {
            $local = strtolower($keyword);
            array_push($DontAdd, $local);
            $TermData = $this->BuildTermEquation($keywordData, $DontAdd);
            if (strpos($local, ' ') !== false) {
                $local = '"' . $local . '"';
            }
            if (strlen($TermData) != 0) {
                $local = '(' . ucfirst($local) . ' OR ' . $TermData . ')';
            }
            $KeywordEquations[$keyword] = $local;
        }
        return $KeywordEquations;
    }


    private function BuildTermEquation(array $KeywordData, array $DontAdd)
    {
        $TermEquation = '';
        foreach ($KeywordData as $term => $DeCSArr) {
            $localterm='';
            if (!(in_array(strtolower($term), $DontAdd))) {
                if (strlen($localterm) > 0) {
                    $localterm = $localterm . ' OR ';
                }
                array_push($DontAdd, strtolower($term));
                if (strpos($term, ' ') !== false) {
                    $term = '"' . $term . '"';
                }
                $localterm = $localterm . ucfirst($term);
            }

            $DeCSArr = array_map('strtolower', $DeCSArr);
            $DeCSArr = array_diff($DeCSArr, $DontAdd);
            $DeCSArr = array_map('ucfirst', $DeCSArr);
            foreach ($DeCSArr as $id => $DeCS) {
                if (strpos($DeCS, ' ') !== false) {
                    $DeCSArr[$id] = '"' . $DeCS . '"';
                }
            }
            $localterm =$localterm. join(' OR ', $DeCSArr);
            if (strlen($localterm) > 0) {
                $localterm='('.$localterm.')';
            }
            if (strlen($TermEquation) > 0) {
                $localterm=' OR '. $localterm;
            }
            $TermEquation=$TermEquation.$localterm;

        }
        return $TermEquation;
    }

}
