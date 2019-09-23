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

    protected function MixDescriptors(DataTransferObject $DTO,array $NewSelectedDescriptors){
        $SelectedDescriptors= $DTO->getAttr('decodedOldDescriptors');
        if(count($SelectedDescriptors)===0){
            return $NewSelectedDescriptors;
        }
        foreach ($NewSelectedDescriptors as $keyword => $keywordData) {
            if (!(array_key_exists($keyword, $SelectedDescriptors))) {
                $SelectedDescriptors[$keyword] = $NewSelectedDescriptors[$keyword];
            }else {
                foreach ($keywordData as $term => $content) {
                    if (!(array_key_exists($term, $SelectedDescriptors[$keyword]))) {
                        $SelectedDescriptors[$keyword][$term] = $content;
                    }else{
                        $SelectedDescriptors[$keyword][$term] = array_merge(array_unique($SelectedDescriptors[$keyword][$term],$content));
                    }
                }
            }
        }
        return $SelectedDescriptors;
    }


    protected function BuildBaseEquation(DataTransferObject $DTO,array $SelectedDescriptors){
        $baseEquation = '';
        foreach ($SelectedDescriptors as $keyword => $keywordData) {
            $local = $keyword;
            $TermData = $this->BuildTermEquation($keywordData);
            if (strlen($TermData) != 0) {
                $local= '('.$local . ' OR '.$TermData.')';
            }
            if (strlen($baseEquation) != 0) {
                $baseEquation = $baseEquation . ' OR ';
            }
            $baseEquation = $baseEquation .$local;
        }
        if (strlen($baseEquation) != 0) {
            $baseEquation = '(' . $baseEquation . ')';
        }
        $DTO->SaveToModel(get_class($this),['newQuery' => $baseEquation]);
    }

    protected function decodeOldSelectedDescriptors(DataTransferObject $DTO, string $undecodedPreviousData=null)
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
