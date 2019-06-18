<?php

namespace LayerIntegration;

class ControllerImportRelatedTrees {

    private $ObjectKeywordIntegration;
    private $DeCSImporter;
    private $SimpleLifeMessage;
    private $ExploredTrees;
    private $PendingMainTrees;
    private $PendingDescendants;
    private $MaxDescendantsDepth;
    private $MaxDirectDescendantsPerTrees;
    private $MaxTreesPerKeyword;
    private $langs;

    public function __construct($ObjectKeywordIntegration, $DeCSImporter, $SimpleLifeMessage, $MaxImports,$langs) {
        $this->ObjectKeywordIntegration = $ObjectKeywordIntegration;
        $this->DeCSImporter = $DeCSImporter;
        $this->SimpleLifeMessage = $SimpleLifeMessage;
        $this->PendingMainTrees = array();
        $this->PendingDescendants = array();
        $this->ExploredTrees = array();
        $this->MaxDescendantsDepth = $MaxImports['MaxDescendantsDepth'];
        $this->MaxDirectDescendantsPerTrees = $MaxImports['MaxDirectDescendantsPerTrees'];
        $this->MaxTreesPerKeyword = $MaxImports['MaxTreesPerKeyword'];
        $this->MaxTreesPerKeyword = $MaxImports['MaxTreesPerKeyword'];
        $this->langs=$langs;
    }

    ///////////////////////////////////////////////////////////////////
    //PUBLIC FUNCTIONS
    /////////////////////////////////////////////////////////////////// 

    public function ExploreMainTree() {
        $this->ExploreMainTreeInner();
    }

    public function ExploreRelatedTreesAndDescendants() {
        $countx = 1;
        while (true) {
            $TreesToExplore = $this->getTreesToExplore();
            if (count($TreesToExplore) == 0) {
                break;
            }
            $ExploreInfo = current((Array) $TreesToExplore);
            $this->ExploreRelatedTreesAndDescendantsLoop($ExploreInfo['tree_id'], $ExploreInfo['isMain']);
            $countx++;
        }
    }

    ///////////////////////////////////////////////////////////////////
    //INNER FUNCTIONS
    /////////////////////////////////////////////////////////////////// 

    private function getTreesToExplore() {
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

    private function ExploreRelatedTreesAndDescendantsLoop($tree_id, $isMain) {
        $results = array();
        $this->ExploreTree($tree_id, $results);
        $this->UpdateExploredTrees($tree_id, $results, $isMain);
    }

    private function ExploreMainTreeInner() {
        $results = array();
        $this->ExploreMainElement($results);
        $this->UpdateExploredTrees('MainTree', $results, true);
        $ToExplore = array();
        $this->CheckLangsOfEachResultsTreeId($ToExplore);
        $results = array();
        $this->ExploreUnexploredLanguagesPerTreeId($results, $ToExplore);
        $this->UpdateExploredTrees('MainTree', $results, true,true);
    }

    private function CheckLangsOfEachResultsTreeId(&$ToExplore) {
        $MainTrees=$this->ObjectKeywordIntegration->getMainTreeList();
        $this->langs;
        foreach ($MainTrees as $tree_id => $TreeObject) {
            $ExploredLanguages = $TreeObject->getExploredLanguages();
            $RemainingLanguages = array_diff($this->langs, $ExploredLanguages);
            $ToExplore[$tree_id] = $RemainingLanguages;
        }
    }

    private function ExploreUnexploredLanguagesPerTreeId(&$results, $ToExplore) {
        foreach ($ToExplore as $tree_id => $RemainingLanguagesArr) {
            if(count($RemainingLanguagesArr)==0){
                continue;
            }
            $this->ExploreTreeMainComplement($tree_id, $results,$RemainingLanguagesArr);
        }
    }

    private function getExploredTrees() {
        return $this->ExploredTrees;
    }

    private function ExploreMainElement(&$results) {
        $this->DeCSImporter->ExploreTreeId($this->ObjectKeywordIntegration->getKeyword(), true, array(), $results);
    }

    private function ExploreTree($tree_id, &$results) {
        $this->DeCSImporter->ExploreTreeId($tree_id, false, $this->getExploredTrees(), $results);
    }
    
        private function ExploreTreeMainComplement($tree_id, &$results,$langs) {
        $this->DeCSImporter->ExploreTreeId($tree_id, false, array(), $results,$langs);
    }

    private function UpdateExploredTrees($callerTreeId, $results, $isMain,$MainComplement=false) {
        $AddedDescendants = array();
        $AddedRelatedTrees = array();
        foreach ($results as $tree_id => $TreeData) {
            $this->AddCurrentTreeToExplored($tree_id);
            if ($isMain && (!($MainComplement))) {
                if (count($this->ObjectKeywordIntegration->getMainTreeList()) >= $this->MaxTreesPerKeyword) {
                    break;
                }
            }
            $this->AddContentAndDescendantsByTreeId($tree_id, $TreeData['content'], $TreeData['descendants'], $callerTreeId, $AddedDescendants, $isMain);
            $AddedRelatedTrees = array_merge($AddedRelatedTrees, $TreeData['relatedtrees']);
        }
        $this->SaveRelatedTreesAndDescendantsToList($callerTreeId, $AddedDescendants, $AddedRelatedTrees);
    }

    private function SaveInfoToTree($tree_id, $callerTreeId, $content, $descendants) {
        $this->ObjectKeywordIntegration->SaveInfoToTree($tree_id, $callerTreeId, $content, $descendants);
    }

    private function AddContentAndDescendantsByTreeId($tree_id, $content, $descendants, $callerTreeId, &$AddedDescendants, $isMain) {
        if (!($isMain)) {
            $levelExplore = $this->ObjectKeywordIntegration->getTreeIdLevelIntoTreesArray($tree_id);
            if ($levelExplore >= $this->MaxDescendantsDepth) {
                $this->SaveInfoToTree($tree_id, $callerTreeId, $content, array());
                return;
            }
            $DescendantText = ' (Descendant Level ' . $levelExplore . ')';
        } else {
            $DescendantText = ' (Main Tree)';
        }
        $descendants = array_slice($descendants, 0, $this->MaxDirectDescendantsPerTrees, true);
        $AddedDescendants = array_merge($AddedDescendants, $descendants);
        $this->SaveInfoToTree($tree_id, $callerTreeId, $content, $descendants);

        $this->SimpleLifeMessage->AddAsNewLine('[' . $tree_id . $DescendantText . '] ->  ' . json_encode($content));
    }

    private function AddCurrentTreeToExplored($tree_id) {
        array_push($this->ExploredTrees, $tree_id);
        $this->PendingDescendants = array_diff($this->PendingDescendants, array($tree_id));
        $this->PendingMainTrees = array_diff($this->PendingMainTrees, array($tree_id));
    }

    private function SaveRelatedTreesAndDescendantsToList($callerTreeId, $AddedDescendants, $AddedRelatedTrees) {
        $descendants = array_unique($AddedDescendants);
        $descendants = array_diff($descendants, $this->PendingDescendants);
        $descendants = array_diff($descendants, $this->ExploredTrees);
        $this->PendingDescendants = array_merge($this->PendingDescendants, $descendants);

        $MainList = array_keys($this->ObjectKeywordIntegration->getMainTreeList());
        $AbleToAdd = $this->MaxTreesPerKeyword - count($MainList);
        $relatedtrees = array();

        if ($AbleToAdd > 0) {
            $relatedtrees = array_unique($AddedRelatedTrees);
            $relatedtrees = array_diff($relatedtrees, $MainList);
            $relatedtrees = array_diff($relatedtrees, $this->ExploredTrees);
            $relatedtrees = array_slice($relatedtrees, 0, $AbleToAdd, true);
            $this->PendingMainTrees = array_merge($this->PendingMainTrees, $relatedtrees);
        }
        $this->SimpleLifeMessage->AddAsNewLine('[' . $callerTreeId . '] Added descendants:{' . join($descendants, ', ') . '} Global Trees:{' . join($relatedtrees, ', ') . '}');
    }

}

?>