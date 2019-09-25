<?php

namespace PICOExplorer\Services\QueryBuild;

use PICOExplorer\Exceptions\Exceptions\AppError\PreviousDataCouldNotBeDecoded;
use PICOExplorer\Exceptions\Exceptions\ClientError\QuerySplitCouldNotBeDecoded;
use PICOExplorer\Exceptions\Exceptions\ClientError\QuerySplitDoesNotExist;
use PICOExplorer\Models\DataTransferObject;
use PICOExplorer\Services\ServiceModels\PICOQueryProcessorTrait;
use PICOExplorer\Services\ServiceModels\ServiceEntryPoint;


abstract class QueryBuildSupport extends ServiceEntryPoint
{

    use PICOQueryProcessorTrait;

    ///////////////////////////////////////////////////////////////////
    //protected FUNCTIONS
    ///////////////////////////////////////////////////////////////////

    protected function MixDescriptors(DataTransferObject $DTO, array $NewSelectedDescriptors)
    {
        $SelectedDescriptors = $DTO->getAttr('decodedOldDescriptors');
        if (count($SelectedDescriptors) === 0) {
            return $NewSelectedDescriptors;
        }
        foreach ($NewSelectedDescriptors as $keyword => $keywordData) {
            if (!(array_key_exists($keyword, $SelectedDescriptors))) {
                $SelectedDescriptors[$keyword] = $NewSelectedDescriptors[$keyword];
            } else {
                foreach ($keywordData as $term => $content) {
                    if (!(array_key_exists($term, $SelectedDescriptors[$keyword]))) {
                        $SelectedDescriptors[$keyword][$term] = $content;
                    } else {
                        $SelectedDescriptors[$keyword][$term] = array_merge(array_unique($SelectedDescriptors[$keyword][$term], $content));
                    }
                }
            }
        }
        return $SelectedDescriptors;
    }


    protected function BuildBaseEquation(DataTransferObject $DTO, array $SelectedDescriptors, array $QuerySplit)
    {
        $baseEquation = '';
        $keywordEquations = $this->BuildKeywordEquations($SelectedDescriptors);
        foreach ($QuerySplit as $wordData) {
            $type = $wordData['type'];
            $word = $wordData['value'];
            if ($type === 'decs' || $type === 'term') {
                continue;
            }
            if ($type === 'keyrep' || $type === 'keyword' || $type === 'keyexplored' || $type === 'keypartial') {
                $keywordeq = $keywordEquations[$word] ?? null;
                if ($keywordeq) {
                    $keywordeq = '(' . $keywordeq . ')';
                } else {
                    $keywordeq = $word;
                }
                $baseEquation = $baseEquation . $keywordeq;
            } else {
                $baseEquation = $baseEquation . $word;
            }
        }
        if (strlen($baseEquation) != 0) {
            $baseEquation = '(' . $baseEquation . ')';
        }
        $DTO->SaveToModel(get_class($this), ['newQuery' => $baseEquation]);
    }

    protected function BuildKeywordEquations(array $SelectedDescriptors)
    {
        $KeywordEquations = [];
        foreach ($SelectedDescriptors as $keyword => $keywordData) {
            $local = $keyword;
            $TermData = $this->BuildTermEquation($keywordData);
            if (strlen($TermData) != 0) {
                $local = '(' . $local . ' OR ' . $TermData . ')';
            }
            $KeywordEquations[$keyword] =$local;
        }
        return $KeywordEquations;
    }

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

    protected function decodeOldSelectedDescriptors(DataTransferObject $DTO, string $undecodedPreviousData = null)
    {
        $decodedOldDescriptors = null;
        if ($undecodedPreviousData) {
            try {
                $decodedOldDescriptors = json_decode($undecodedPreviousData, true);
            } catch (\Throwable $ex) {
                throw new PreviousDataCouldNotBeDecoded(['PreviousData' => json_encode($undecodedPreviousData)], $ex);
            }
        } else {
            $decodedOldDescriptors = [];
        }
        $DTO->SaveToModel(get_class($this), ['decodedOldDescriptors' => $decodedOldDescriptors]);
    }

    protected function ImproveBasicEquation(DataTransferObject $DTO, string $ImproveSearchQuery)
    {
        if (strlen($ImproveSearchQuery) === 0) {
            return;
        }
        $newQuery = $DTO->getAttr('newQuery');
        $newQuery = $newQuery .' OR ('. $ImproveSearchQuery.')';
        $DTO->SaveToModel(get_class($this), ['newQuery' => $newQuery]);
    }



    /////////////////////////////////////
    /// INNER
    /// ///////////////////////////


    private function BuildTermEquation(array $KeywordData)
    {
        $TermEquation = '';
        foreach ($KeywordData as $term => $DeCSArr) {
            foreach ($DeCSArr as $id => $DeCS) {
                if (strpos($DeCS, ' ') !== false) {
                    $DeCS = '"' . $DeCS . '"';
                }
                if (strlen($TermEquation) > 0) {
                    $TermEquation = $TermEquation . ' OR ';
                }
                $TermEquation = $TermEquation . ucfirst($DeCS);
            }
        }
        return $TermEquation;
    }

}
