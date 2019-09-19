<?php

namespace PICOExplorer\Services\DeCSIntegration;

use PICOExplorer\Models\DataTransferObject;

abstract class DeCSIntegrationDataProcessor extends DTOManager
{

    protected function getTreesWithUnexploredLanguages(DataTransferObject $DTO)
    {
        $ToExplore = [];
        $ResultsByTreeId = $this->getDataByTreeId($DTO);
        foreach ($ResultsByTreeId as $tree_id => $TreeData) {
            $ExploredLanguages = array_keys($TreeData);
            $RemainingLanguages = array_diff($this->getLangs($DTO), $ExploredLanguages);
            $ToExplore[$tree_id] = $RemainingLanguages;
        }
        return $ToExplore;
    }

    protected function ProcessImportResults(array $XMLresults, string $lang, bool $IsMainTree,DataTransferObject $DTO)
    {
        $decs = $XMLresults['decs'];
        $decs = $this->RemoveCommasFromDeCS($decs);
        $term = $XMLresults['term'];
        $tree_id = $XMLresults['tree_id'];
        $this->addToExploredTrees($tree_id,$DTO);
        $descendants=[];
        $RemainingDescendants = $this->getRemainingDescendants($tree_id,$DTO);
        if ($RemainingDescendants && $this->ShouldISaveDescendants($IsMainTree, $tree_id,$DTO)) {
            $descendants=$this->DescendantsToSave($tree_id, $XMLresults['descendants'] ?? [], $RemainingDescendants,$DTO);
        }
        $this->AddDataToResults($tree_id, $lang, $term, $decs,$descendants,$DTO);
        $trees = $XMLresults['trees'] ?? [];
        $RemainingMainTrees = $this->getRemainingMainTrees($DTO);
        if ($RemainingMainTrees && $this->ShouldISaveMainTrees($IsMainTree)) {
            $this->SaveMainTreesToExplore($descendants, $trees, $RemainingMainTrees,$DTO);
        }
    }

    ///////////////////////////////////////////////////////////////////
    //INNER FUNCTIONS
    ///////////////////////////////////////////////////////////////////

    private function getRemainingMainTrees(DataTransferObject $DTO)
    {
        $limit = $this->getMaxTreesPerKeyword() - count($this->getMainTreeList($DTO));
        if ($limit < 0) {
            return 0;
        }
        return $limit;
    }

    private function getRemainingDescendants(string $tree_id,DataTransferObject $DTO)
    {
        $limit = $this->getMaxDescendantsPerTree() - count($this->getTreeDescendants($tree_id,$DTO));
        if ($limit < 0) {
            return 0;
        }
        return $limit;
    }

    private function ShouldISaveMainTrees(bool $IsMainTree)
    {
        if ($IsMainTree === true) {
            return true;
        } else {
            if ($this->ShouldIAddDescendantsToMainTrees()) {
                return true;
            }
        }
        return false;
    }

    private function ShouldISaveDescendants(bool $IsMainTree, string $tree_id,DataTransferObject $DTO)
    {
        if ($IsMainTree) {
            return true;
        }
        $depth = $this->getTreeIdDepth($tree_id,$DTO) + 1;
        if ($depth === -1) {
            $this->addLogError('Parent Tree id was not found in the search algoryhtm...', $tree_id,$DTO);
            return false;
        }
        $maxdepth = $this->getMaxDescendantsDepth();
        if ($depth <= $maxdepth) {
            return true;
        } else {
            $this->addLogLine('Max depth level (' . $maxdepth . ') reached in tree_id ' . $tree_id,$DTO);
            return false;
        }
    }

    private function SaveMainTreesToExplore(array $descendants, array $trees, int $RemainingMainTrees,DataTransferObject $DTO)
    {
        if ($this->ShouldIAddRelatedTreesToMainTrees()) {
            $descendants = array_unique(array_merge($descendants, $trees));
        }
        if (!(count($descendants))) {
            return;
        }
        $descendants = array_slice($descendants, 0, $RemainingMainTrees, true);
        if (!(count($descendants))) {
            return;
        }
        $this->addLogData('Saving to MainTrees to explore... ', $descendants,$DTO);
        $this->addToUnexploredMainTrees($descendants,$DTO);
        $this->addToMainTreeList($descendants,$DTO);
    }

    private function DescendantsToSave(string $tree_id, array $descendants, int $RemainingDescendants,DataTransferObject $DTO)
    {
        $descendants = array_slice($descendants, 0, $RemainingDescendants, true);
        if (!(count($descendants))) {
            return [];
        }
        $descendants = array_diff($descendants, $this->getTreeDescendants($tree_id,$DTO)??[]);
        if (!(count($descendants))) {
            return [];
        }
        $this->addLogData('Tree:' . $tree_id . ' Saving to descendants... ', $descendants,$DTO);
        $this->addToUnexploredDescendants($descendants, $DTO);
        return $descendants;
    }

    private function RemoveCommasFromDeCS(array $DeCSarray)
    {
        $results = [];
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
            array_push($results, $DeCSWord);
        }
        return array_unique($results);
    }

    ///$keywordDataFromBireme => [
    ///   $tree_id =>[
    ///     'descendants' --> ([]String)
    ///      $lang1=[
    ///         'term' --> (String)
    ///         'decs' --> ([]String)
    ///      ],
    ///      $lang2=[
    ///         'term' --> (String)
    ///         'decs' --> ([]String)
    ///      ],
    ///   ]
    ///],

    private function getTreeIdDepth(string $tree_id,DataTransferObject $DTO)
    {
        $result = 999;
        $main_tree_ids = $this->getExploredMainTrees($DTO);
        $ResultsByTreeId = $this->getDataByTreeId($DTO);
        $maintrees = [];
        foreach ($ResultsByTreeId as $tree_id => $data) {
            if (array_key_exists($tree_id, $main_tree_ids)) {
                $maintrees[$tree_id] = $data;
            }
        }
        $this->FindTreeIdLevelIntoTreesArray($ResultsByTreeId, $maintrees, $tree_id, 0, $result);
        if ($results = 999) {
            $result = -1;
        }
        return $result;
    }

    protected function getTreeObject(string $tree_id, $DTO){
        $ResultsByTreeId = $this->getDataByTreeId($DTO);
        $TreeObject = $ResultsByTreeId[$tree_id] ?? null;
        if (!($TreeObject)) {
            $this->addLogError('Couldnt get TreeObject. Unexistant treeid ' . $tree_id . ' in ',json_encode(array_keys($ResultsByTreeId)),$DTO);
            return null;
        } else {
            return $TreeObject;
        }
    }

    protected function getTreeDescendants(string $tree_id, $DTO)
    {
        $TreeObject=$this->getTreeObject($tree_id,$DTO);
        if($TreeObject){
            return $TreeObject['descendants'];
        }else{
            return [];
        }
    }

    private function FindTreeIdLevelIntoTreesArray(array $ResultsByTreeId, array $descendants, string $tree_id, int $level, int &$result)
    {
        if (count($descendants) === 0) {
            return;
        }
        $level++;
        if ($level > $this->getMaxDescendantsDepth()) {
            return;
        }
        if (in_array($tree_id, $descendants)) {
            if ($level < $result) {
                $result = $level;
            }
            return;
        }
        foreach ($descendants as $descendant) {
            $TreeObject = $ResultsByTreeId[$descendant] ?? null;
            if (!($TreeObject)) {
                continue;
            }
            $NewDescendants = $TreeObject['descendants'];
            $this->FindTreeIdLevelIntoTreesArray($ResultsByTreeId, $NewDescendants, $tree_id, $level, $result);
        }
    }

}
