<?php

namespace PICOExplorer\Services\DeCSIntegration;

use PICOExplorer\Services\AdvancedLogger\Traits\SpecialValidator;
use PICOExplorer\Services\ServiceModels\ExternalImporter;

abstract class DeCSIntegrationProcessor extends ExternalImporter
{

    protected $attributes = [];

    use SpecialValidator;

    protected function ProcessImportResults(array $TmpResults, string $lang, bool $IsMainTree, string $referer)
    {
        $relatedtrees = [];
        $ExploredTreeIds = [];
        $finaldescendants = [];
        foreach ($TmpResults as $TmpResultsData) {
            $term = $TmpResultsData['term'];
            $tree_id = $TmpResultsData['tree_id'];
            $trees = $TmpResultsData['trees'] ?? [];
            $relatedtrees = array_merge($relatedtrees, $trees);
            $descendants = $TmpResultsData['descendants'] ?? [];
            $decs = $this->RemoveCommasFromDeCS($TmpResultsData['decs']);

            if ($IsMainTree === true && (count($this->model->MainTreeList) >= $this->attributes['MaxTreesPerKeyword'])) {
                continue;
            }
            $this->model->TreeManager($tree_id, $lang, $term, $decs, $referer, $IsMainTree);
            array_push($ExploredTreeIds, $tree_id);
            array_push($ExploredTreeIds, $tree_id);
            $this->ProcessDescendants($tree_id, $descendants, $IsMainTree, $finaldescendants);
        }
        $this->updateTreesToExplore($relatedtrees, $ExploredTreeIds, $finaldescendants);
    }

    ///////////////////////////////////////////////////////////////////
    //INNER FUNCTIONS
    ///////////////////////////////////////////////////////////////////

    private function ProcessDescendants(string $tree_id, array $descendants, bool $IsMainTree, array &$finaldescendants)
    {
        if (!($IsMainTree)) {
            $levelExplore = $this->getTreeIdLevelIntoTreesArray($tree_id);
            if ($levelExplore >= $this->attributes['MaxDescendantsDepth']) {
                return;
            }
        }
        $descendants = array_slice($descendants, 0, $this->attributes['MaxDirectDescendantsPerTrees'], true);
        $finaldescendants = array_unique(array_merge($finaldescendants, $descendants));
        if (count($descendants) > 0) {
            $this->model->TreeDescendants($tree_id, $descendants);
        }
    }

    private function getTreeIdLevelIntoTreesArray($tree_id)
    {
        $result = 999;
        $this->model->FindTreeIdLevelIntoTreesArray($this->model->MainTreeList, $tree_id, 0, $result);
        return $result;
    }

    private function updateTreesToExplore(array $relatedtrees, array $ExploredTreeIds, array $descendants)
    {
        $this->processExplored($ExploredTreeIds);
        $oldTrees = array_merge($this->model->ExploredTrees, $this->model->PendingDescendants, $this->model->PendingMainTrees);

        $relatedtrees = array_unique($relatedtrees);
        $relatedtrees = array_diff($relatedtrees, $oldTrees);
        $descendants = array_unique($descendants);
        $descendants = array_diff($descendants, $oldTrees);

        if (count($relatedtrees) > 0) {
            $AbleToAdd = $this->attributes['MaxTreesPerKeyword'] - count(array_keys($this->model->MainTreeList));
            if ($AbleToAdd > 0) {
                $relatedtrees = array_slice($relatedtrees, 0, $AbleToAdd, true);
                $relatedtrees = array_merge($this->model->PendingMainTrees, $relatedtrees);
                $this->model->setAttribute('PendingMainTrees', $relatedtrees);
            }
        }
        if (count($descendants) > 0) {
            $this->model->setAttribute('PendingDescendants', $descendants);
        }
    }

    private function processExplored(array $ExploredTreeIds)
    {
        $this->model->setAttribute('ExploredTrees', array_unique(array_merge($this->model->ExploredTrees, $ExploredTreeIds)));
        $this->model->setAttribute('PendingDescendants', array_diff($this->model->PendingDescendants, $ExploredTreeIds));
        $this->model->setAttribute('PendingMainTrees', array_diff($this->model->PendingMainTrees, $ExploredTreeIds));
    }

    private function RemoveCommasFromDeCS(array $DeCSarray)
    {
        $Result = [];
        foreach ($DeCSarray as $DeCSWord) {
            $DeCSWordSplitByComma = explode(', ', $DeCSWord);
            if (count($DeCSWordSplitByComma) > 1) {
                $NumberOfCommas = count($DeCSWordSplitByComma) - 1;
                $DeCSWordReOrderedWithoutComma = '';
                while ($NumberOfCommas >= 0) {
                    if (strlen($DeCSWordReOrderedWithoutComma) > 0) {
                        $DeCSWordReOrderedWithoutComma = $DeCSWordReOrderedWithoutComma . ' ';
                    }
                    $DeCSWordReOrderedWithoutComma = $DeCSWordReOrderedWithoutComma . $DeCSWordSplitByComma[$NumberOfCommas];
                    $NumberOfCommas--;
                }
                $DeCSWord = $DeCSWordReOrderedWithoutComma;
            }
            array_push($Result,$DeCSWord);
        }
        return array_unique($Result);
    }

}
