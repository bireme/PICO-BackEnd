<?php

namespace PICOExplorer\Services\QueryBuild;

use PICOExplorer\Exceptions\Exceptions\AppError\KeywordDoesNotExistInSelectedDescriptors;
use PICOExplorer\Services\ServiceModels\PICOServiceModel;

abstract class QueryBuildBase extends PICOServiceModel
{

    protected function buildBaseEquation(){
        $baseEquation = '';
        $Ops = array('or', 'and', 'not');
        $Seps = array('(', ')', ' ', ':');
        foreach ($this->DTO->getInitialData()['QuerySplit'] as $QuerySplitItem) {
            $type = $QuerySplitItem['type'];
            $value = $QuerySplitItem['value'];
            if ($type == 'key' || $type == 'keyrep' || $type == 'keyexplored') {
                $baseEquation = $baseEquation . $this->BuildKeyWordEquation($value);
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

    protected function ImproveBasicEquation(string $baseEquation, array $ProcessedImprovedSearchQuery)
    {
        return $baseEquation;
    }

    protected function BuildKeyWordEquation(string $keyword)
    {
        $keywordEquation = ucfirst($keyword);
        if (!(array_key_exists($keyword, $this->DTO->getInitialData()['SelectedDescriptors']))) {
            throw new KeywordDoesNotExistInSelectedDescriptors(['keyword' => $keyword, 'SelectedDescriptors' => $this->DTO->getInitialData()['SelectedDescriptors']]);
        }
        $KeywordObj = $this->DTO->getInitialData()['SelectedDescriptors'][$keyword];
        foreach ($KeywordObj as $termObj) {

            if (strlen($keywordEquation) != 0) {
                $keywordEquation = $keywordEquation . ' OR ';
            }
            $keywordEquation = $keywordEquation . $this->BuildTermEquation($termObj);
        }
        if (strlen($keywordEquation) != 0) {
            $keywordEquation = '(' . $keywordEquation . ')';
        }
        return $keywordEquation;
    }

    protected function BuildTermEquation(array $termObj)
    {
        $TermEquation = '';
        foreach ($termObj as $DeCS) {
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
