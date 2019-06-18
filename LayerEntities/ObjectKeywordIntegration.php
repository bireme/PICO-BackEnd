<?php

namespace LayerEntities;

require_once('TreeObject.php');

use LayerEntities\TreeObject;

class ObjectKeywordIntegration {

    private $keyword;
    private $MainTreeList;
    private $AllTreeList;

    public function __construct($keyword) {
        $this->keyword = $keyword;
        $this->MainTreeList = array();
        $this->AllTreeList = array();
    }

    public function getKeyword() {
        return $this->keyword;
    }

    public function getMainTreeList() {
        return $this->MainTreeList;
    }

    public function IsMainTree($tree_id) {
        return in_array($tree_id, array_keys($this->MainTreeList));
    }

    public function InAllList($tree_id) {
        return in_array($tree_id, array_keys($this->AllTreeList));
    }

    public function SaveInfoToTree($tree_id, $callerTreeId, $content = NULL, $descendants = array()) {
        $CurrentTree = NULL;
        $ParentTree = NULL;
        if ($callerTreeId == !'MainTree' && (!($this->InAllList($callerTreeId)))) {
            throw 'error in algorith';
        }
        if ($this->InAllList($tree_id)) {
            $CurrentTree = $this->AllTreeList[$tree_id];
        } else {
            $CurrentTree = new TreeObject($tree_id);
            $this->AllTreeList[$tree_id] = $CurrentTree;
        }
        if ($callerTreeId === 'MainTree' && (!($this->IsMainTree($tree_id)))) {
            $this->MainTreeList[$tree_id] = $CurrentTree;
        }

        $DescendantArray = array();
        foreach ($descendants as $descendantstree_id) {
            if ($this->InAllList($descendantstree_id)) {
                $TmpDescendantTree = $this->AllTreeList[$descendantstree_id];
            } else {
                $TmpDescendantTree = new TreeObject($descendantstree_id);
                $this->AllTreeList[$descendantstree_id] = $TmpDescendantTree;
            }
            $DescendantArray[$descendantstree_id]= $TmpDescendantTree;
        }
        $CurrentTree->setContent($DescendantArray, $content);
    }

    private function FindTreeIdLevelIntoTreesArray($TreeArray, $tree_id, $level, &$result) {
        $level++;
        if (in_array($tree_id, array_keys($TreeArray))) {
            if ($level < $result) {
                $result = $level;
            }
            return;
        }
        foreach ($TreeArray as $TreeArraytree_id => $MainTreeObject) {
            $descendants = $MainTreeObject->getDescendants();
            if (count($descendants) == 0) {
                continue;
            }
            $this->FindTreeIdLevelIntoTreesArray($descendants, $tree_id, $level, $result);
        }
    }

    public function getTreeIdLevelIntoTreesArray($tree_id) {
        $result = 999;
        $this->FindTreeIdLevelIntoTreesArray($this->MainTreeList, $tree_id, 0, $result);
        return $result;
    }

    public function getTreeResults($tree_id) {
        if (!($this->InAllList($tree_id))) {
            throw 'error in algorithm';
        }
        return $this->MainTreeList[$tree_id]->getResults();
    }

    public function getResults() {
        $result = array();
        foreach ($this->MainTreeList as $tree_id => $MainTreeObject) {
            $result[$tree_id] = $MainTreeObject->getInnerDescendantResults();
        }
        return $result;
    }

}

?>