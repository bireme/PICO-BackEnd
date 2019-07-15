<?php

namespace PICOExplorer\Services\DeCSIntegration;

class DeCSBIREME extends DeCSBIREMECoordinator
{

    protected $MaxDescendantsDepth;
    protected $MaxDirectDescendantsPerTrees;
    protected $MaxTreesPerKeyword;
    protected $PendingDescendants;
    protected $PendingMainTrees;
    protected $ExploredTrees;

    final public function Process()
    {
        parent::Process();
    }

    protected function Explore($arguments=null)
    {
        //Explore Main Tree
        $results = array();
        $this->ExploreTreeId($this->model->Keyword, true, array(), $results);
        $this->UpdateExploredTrees('MainTree', $results, true);
        $ToExplore = array();
        $this->CheckLangsOfEachResultsTreeId($ToExplore);
        $results = array();
        $this->ExploreUnexploredLanguagesPerTreeId($results, $ToExplore);
        $this->UpdateExploredTrees('MainTree', $results, true, true);

        //Explore Related Trees And Desecendants
        while (true) {
            $TreesToExplore = $this->getTreesToExplore();
            if (count($TreesToExplore) == 0) {
                break;
            }
            $ExploreInfo = current((Array)$TreesToExplore);

            //Explore Tree Descendants And Loops
            $results = array();
            $this->ExploreTreeId($ExploreInfo['tree_id'], false, $this->ExploredTrees, $results);
            $this->UpdateExploredTrees($ExploreInfo['tree_id'], $results, $ExploreInfo['isMain']);
        }
    }

    ///////////////////////////////////////////////////////////////////
    //INNER FUNCTIONS
    ///////////////////////////////////////////////////////////////////


    private function getTreesToExplore()
    {
        $PriorityArr = array_intersect($this->PendingDescendants, $this->PendingMainTrees);
        $OrderedOperationArr = array_merge($this->PendingDescendants, $this->PendingMainTrees);
        $RefinedOperationrArr = array_unique(array_merge($PriorityArr, $OrderedOperationArr));
        $FinalArr = array();
        foreach ($RefinedOperationrArr as $tree_id) {
            $info = array('tree_id' => $tree_id, 'isMain' => in_array($tree_id, $this->PendingMainTrees));
            array_push($FinalArr, $info);
        }
        return $FinalArr;
    }

    private function CheckLangsOfEachResultsTreeId(&$ToExplore)
    {
        $MainTrees = $this->model->MainTreeList;
        foreach ($MainTrees as $tree_id => $TreeObject) {
            $ExploredLanguages = $TreeObject->ExploredLanguages;
            $RemainingLanguages = array_diff($this->model->langs, $ExploredLanguages);
            $ToExplore[$tree_id] = $RemainingLanguages;
        }
    }

    private function ExploreUnexploredLanguagesPerTreeId(&$results, $ToExplore)
    {
        foreach ($ToExplore as $tree_id => $RemainingLanguagesArr) {
            if (count($RemainingLanguagesArr) > 0) {
                $this->ExploreTreeId($tree_id, false, array(), $results, $RemainingLanguagesArr);
            }
        }
    }

    private function UpdateExploredTrees($callerTreeId, $results, $isMain, $MainComplement = false)
    {
        $AddedDescendants = array();
        $AddedRelatedTrees = array();
        foreach ($results as $tree_id => $TreeData) {

            //////////////AddCurrentTreeToExplored
            array_push($this->ExploredTrees, $tree_id);
            $this->PendingDescendants = array_diff($this->PendingDescendants, array($tree_id));
            $this->PendingMainTrees = array_diff($this->PendingMainTrees, array($tree_id));

            if ($isMain && (!($MainComplement))) {
                if (count($this->model->MainTreeList) >= $this->MaxTreesPerKeyword) {
                    break;
                }
            }
            $this->AddContentAndDescendantsByTreeId($tree_id, $TreeData['content'], $TreeData['descendants'], $callerTreeId, $AddedDescendants, $isMain);
            $AddedRelatedTrees = array_merge($AddedRelatedTrees, $TreeData['relatedtrees']);
        }
        $this->SaveRelatedTreesAndDescendantsToList($AddedDescendants, $AddedRelatedTrees);
    }

    private function AddContentAndDescendantsByTreeId($tree_id, $content, $descendants, $callerTreeId, &$AddedDescendants, $isMain)
    {
        if (!($isMain)) {
            $levelExplore = $this->model->getTreeIdLevelIntoTreesArray($tree_id);
            if ($levelExplore >= $this->MaxDescendantsDepth) {
                $this->UpdateModel(['tree_id'=>$tree_id,'callerTreeId'=>$callerTreeId, 'content'=>$content, 'descendants'=>$descendants],false);
                return;
            }
        }
        $descendants = array_slice($descendants, 0, $this->MaxDirectDescendantsPerTrees, true);
        $AddedDescendants = array_merge($AddedDescendants, $descendants);
        $this->UpdateModel(['tree_id'=>$tree_id,'callerTreeId'=>$callerTreeId, 'content'=>$content, 'descendants'=>$descendants],false);
    }

    private function SaveRelatedTreesAndDescendantsToList($AddedDescendants, $AddedRelatedTrees)
    {
        $descendants = array_unique($AddedDescendants);
        $descendants = array_diff($descendants, $this->PendingDescendants);
        $descendants = array_diff($descendants, $this->ExploredTrees);
        $this->PendingDescendants = array_merge($this->PendingDescendants, $descendants);
        $MainList = $this->model->TreeList;
        $AbleToAdd = $this->MaxTreesPerKeyword - count($MainList);
        if ($AbleToAdd > 0) {
            $relatedtrees = array_unique($AddedRelatedTrees);
            $relatedtrees = array_diff($relatedtrees, $MainList);
            $relatedtrees = array_diff($relatedtrees, $this->ExploredTrees);
            $relatedtrees = array_slice($relatedtrees, 0, $AbleToAdd, true);
            $this->PendingMainTrees = array_merge($this->PendingMainTrees, $relatedtrees);
        }
    }

}

