<?php

namespace PICOExplorer\Services\QueryBuild;

use PICOExplorer\Models\DataTransferObject;
use PICOExplorer\Services\ServiceModels\PICOQueryProcessorTrait;
use PICOExplorer\Services\ServiceModels\ServiceEntryPoint;


abstract class QueryBuildSupport extends ServiceEntryPoint
{

    use PICOQueryProcessorTrait;

    ///////////////////////////////////////////////////////////////////
    //protected FUNCTIONS
    ///////////////////////////////////////////////////////////////////

    protected function BuildBaseEquation(DataTransferObject $DTO,int $PICOnum,$QuerySplit,$DeCSResults,$SelectedDescriptors){
        $baseEquation = '';
        $Ops = array('or', 'and', 'not');
        $Seps = array('(', ')', ' ', ':');
        foreach ($QuerySplit as $QuerySplitItem) {
            $type = $QuerySplitItem['type'];
            $value = $QuerySplitItem['value'];
            if ($type == 'key' || $type == 'keyrep' || $type == 'keyexplored') {
                $baseEquation = $baseEquation . $this->BuildKeyWordEquation($SelectedDescriptors, $value);
            } else {
                if (in_array($value, $Ops)) {
                    $value = strtoupper($value);
                } else {
                    if (!(in_array($value, $Seps))) {
                        $value = ucfirst($value);
                        if (strpos($value, ' ') !== false) {
                            $value = '"' . $value . '"';
                        }
                    }
                }
                $baseEquation = $baseEquation . $value;
            }
        }
        if (strlen($baseEquation) != 0) {
            $baseEquation = '(' . $baseEquation . ')';
        }
        return $baseEquation;
    }


    protected function ImproveBasicEquation($DTO,$ImproveSearchQuery) //Falta por construir
    {

        $this->ProcessQuery($ImproveSearchQuery);
        $DTO->SaveToModel(get_class($this),['ImprovedEquation' => $ImprovedEquation]);
        return $baseEquation;
    }

    ///////////////////////////////////////////////////////////////////
    //INNER FUNCTIONS
    ///////////////////////////////////////////////////////////////////


    private function BuildKeyWordEquation(array $SelectedDescriptors, string $keyword)
    {
        $keywordEquation = ucfirst($keyword);
        $KeywordData = $SelectedDescriptors[$keyword];
        foreach ($KeywordData as $TermData) {

            if (strlen($keywordEquation) != 0) {
                $keywordEquation = $keywordEquation . ' OR ';
            }
            $keywordEquation = $keywordEquation . $this->BuildTermEquation($TermData);
        }
        if (strlen($keywordEquation) != 0) {
            $keywordEquation = '(' . $keywordEquation . ')';
        }
        return $keywordEquation;
    }

    private function BuildTermEquation(array $KeywordData)
    {
        $TermEquation = '';
        foreach ($KeywordData as $DeCS) {
            if (strpos($DeCS, ' ') !== false) {
                $DeCS = '"' . $DeCS . '"';
            }
            if (strlen($TermEquation) > 0) {
                $TermEquation = $TermEquation . ' OR ';
            }
            $TermEquation = $TermEquation . ucfirst($DeCS);
        }
        if (strlen($TermEquation) > 0) {
            $TermEquation = '(' . $TermEquation . ')';
        }
        return $TermEquation;
    }

}
