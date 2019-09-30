<?php

namespace PICOExplorer\Services\DeCSIntegration;

use PICOExplorer\Models\DataTransferObject;
use PICOExplorer\Services\ServiceModels\ServiceEntryPoint;

abstract class DTOManager extends ServiceEntryPoint
{

    ////////////////////////////////////////////////////////
    /// ControllerData
    ////////////////////////////////////////////////////////

    protected $controllerData = [
        'MaxExploringLoops' => 5,
        'MaxTreesPerKeyword' => 10,
        'MaxDescendantsPerTree' => 10,
        'MaxTotalTrees' => 300,
        'MaxDescendantsDepth' => 5,
        'SaveDescendantsOfPrimaryMainTreesIntoMainTrees' => true,
        'SaveRelatedTreesOfPrimaryMainTreesIntoMainTrees' => true,
        'AllValidLanguages' => [
            'en',
            'es',
            'pt'
        ]
    ];

    ///
    ///GLOBAL CONTROLLER VARS
    ///


    protected function getMaxExploringLoops()
    {
        return $this->controllerData['MaxExploringLoops'];
    }

    protected function getMaxWishList()
    {
        return $this->controllerData['MaxTotalTrees'];
    }

    protected function getMaxDescendantsDepth()
    {
        return $this->controllerData['MaxDescendantsDepth'];
    }


    ///
    ///TREE OR KW
    ///

    protected function getMaxTreesPerKeyword()
    {
        return $this->controllerData['MaxTreesPerKeyword'];
    }

    protected function getMaxDescendantsPerTree()
    {
        return $this->controllerData['MaxDescendantsPerTree'];
    }

    ////////////////////////////////////////////////////////
    /// InitalAttributes
    ////////////////////////////////////////////////////////

    protected function setInitialVars(DataTransferObject $DTO)
    {
        $InitialData = $DTO->getInitialData();
        $keyword = $InitialData['keyword'];
        $keyword = str_replace('  ', ' ', $keyword);
        $keyword = trim($keyword);
        $keyword = str_replace(' ', '+', $keyword);

        $inidata = [
            'langs' => $InitialData['langs'],
            'keyword' => $keyword,
            'MainTreeList' => [],
            'WishList' => [],
            'ResultsByTreeId' => [],
        ];
        $DTO->SaveToModel(get_class($this), $inidata);
    }

    protected function getAllValidLanguages()
    {
        return $this->controllerData['AllValidLanguages'];
    }

    protected function getKeyword(DataTransferObject $DTO)
    {
        return $DTO->getAttr('keyword');
    }

    protected function getLangs(DataTransferObject $DTO)
    {
        return $DTO->getAttr('langs');
    }

    ////////////////////////////////////////////////////////
    /// Simple Getters of trees
    ////////////////////////////////////////////////////////

    protected function getMainTreeList(DataTransferObject $DTO)
    {
        return $DTO->getAttr('MainTreeList');
    }

    protected function getWishList(DataTransferObject $DTO)
    {
        return $DTO->getAttr('WishList');
    }

    protected function getResultsOrderedByTreeId(DataTransferObject $DTO)
    {
        return $DTO->getAttr('ResultsByTreeId');
    }

    protected function saveResultsOrderedByTreeId(DataTransferObject $DTO, array $ResultsOrderedByTreeId)
    {
        $DTO->SaveToModel(get_class($this), ['ResultsByTreeId' => $ResultsOrderedByTreeId]);
    }

    protected function getExploreList(DataTransferObject $DTO)
    {
        $maintrees = $this->getMainTreeList($DTO);
        $wishlist = $this->getWishList($DTO);
        $listorder = array_unique(array_merge($maintrees, $wishlist));

        $currentdata = $this->getResultsOrderedByTreeId($DTO);

        $ExploreList = [];
        foreach ($listorder as $keyword) {
            $currentKeyword = $currentdata[$keyword] ?? null;
            $currentPendingByKeyword = $currentKeyword['remaininglangs'] ?? null;
            if ($currentPendingByKeyword === null) {
                $langs = $this->getLangs($DTO);
            } elseif (count($currentPendingByKeyword)) {
                $langs = $currentPendingByKeyword;
            } else {
                continue;
            }
            $arrayitem = ['key' => $keyword, 'langs' => $langs];
            array_push($ExploreList, $arrayitem);
        }
        return $ExploreList;
    }

    /////////////////////////////////////////////////////
    /// GLOBAL LIMITERS
    ////////////////////////////////////////////////////////

    protected function addToMainTreeList(array $tree_ids, DataTransferObject $DTO)
    {
        $oldmaintrees = $this->getMainTreeList($DTO);
        $remainingmaintrees = $this->getRemainingMainTreesToAdd($DTO);

        $newtrees = array_diff($tree_ids, $oldmaintrees);

        if ($remainingmaintrees) {
            $added = array_slice($newtrees, 0, $remainingmaintrees, true);
        } else {
            $added = [];
        }
        $cut = array_diff($newtrees, $added);

        $info = [
            'added' => $added,
            'cut' => $cut,
        ];
        $DTO->SaveToModel(get_class($this), ['MainTreeList' => array_merge($oldmaintrees,$added)]);
        $this->addToWishList($added, $DTO);
        return $info;
    }

    protected function addToWishList(array $tree_ids, DataTransferObject $DTO)
    {
        $oldwishlist = $this->getWishList($DTO);
        $remainingwishlist = $this->getRemainingWishListToAdd($DTO);

        $newtrees = array_diff($tree_ids, $oldwishlist);

        if ($remainingwishlist) {
            $added = array_slice($newtrees, 0, $remainingwishlist, true);
        } else {
            $added = [];
        }
        $cut = array_diff($newtrees, $added);
        $wishlist = array_merge($oldwishlist, $added);
        $DTO->SaveToModel(get_class($this), ['WishList' => $wishlist]);

        $info = [
            'added' => $added,
            'cut' => $cut,
        ];
        return $info;
    }


    protected function ShouldISaveDescendantsOfPrimaryMainTreesIntoMainTrees()
    {
        return $this->controllerData['SaveDescendantsOfPrimaryMainTreesIntoMainTrees'];
    }

    protected function ShouldISaveRelatedTreesOfPrimaryMainTreesIntoMainTrees()
    {
        return $this->controllerData['SaveRelatedTreesOfPrimaryMainTreesIntoMainTrees'];
    }

    protected function addDescendantsToTree(array $descendants_ids, String $tree_id, bool $isMain, DataTransferObject $DTO)
    {
        if (!($isMain)) {
            $olddescendants = $this->getDescendants($tree_id, $DTO);
            if ($olddescendants === -1) {
                return;
            }
        } else {
            $olddescendants = [];
        }
        $newdescendants = array_diff($descendants_ids, $olddescendants);

        $maxdescendants = $this->getMaxDescendantsPerTree();
        $remainingdescendants = $maxdescendants - count($olddescendants);

        if ($remainingdescendants) {
            $added = array_slice($newdescendants, 0, $remainingdescendants, true);
        } else {
            $added = [];
        }

        $wishinfo = $this->addToWishList($added, $DTO);
        $added = $wishinfo['added'];
        $cut = array_diff($newdescendants, $added);

        $info = [
            'added' => $added,
            'cut' => $cut,
        ];
        return $info;
    }

    protected function WishListType(DataTransferObject $DTO)
    {
        $maintrees = $this->getMainTreeList($DTO);
        $exploredTrees = $this->getResultsOrderedByTreeId($DTO);
        $wishlist = $this->getWishList($DTO);
        $relevant = array_intersect($wishlist,array_keys($exploredTrees));

        $result = [];
        foreach ($relevant as $key => $tree_id) {
            if (in_array($tree_id, $maintrees)) {
                $type = true;
            } else {
                $type = false;
            }
            $TreeObj = $exploredTrees[$tree_id]??null;
            if ($TreeObj) {
                $descendants = $TreeObj['descendants'];
                if (count($descendants) === 0) {
                    continue;
                }
            } else {
                continue;
            }
            $result[$tree_id] = ['type' => $type, 'descendants' => $descendants];
        }
        return $result;
    }


    protected function IsTreeDepthOk(String $tree_id, DataTransferObject $DTO)
    {
        $MainList = $this->getMainTreeList($DTO);
        $maxdepth = $this->getMaxDescendantsDepth();
        if(in_array($tree_id,$MainList)){
            $currentdepth = 1;
        }else{
            $WishListType = $this->WishListType($DTO);
            $result=[];
            $this->getTreeIdDepth($tree_id, $WishListType, 1, $DTO,$result);
            $currentdepth = 999;
            $masterParent = '';
            foreach($result as $itemarray){
                if($itemarray['level']<$currentdepth){
                    $currentdepth = $itemarray['level'];
                    $masterParent = $itemarray['tree_id'];
                }
            }
        }
        $info = [
            'maxdepth' => $maxdepth,
            'currentdepth' => $currentdepth,
        ];
        return $info;
    }

    ///////////////////////////////////////
    /// INNNER
    /// //////////////////////////////////////

    private function getTree(String $tree_id, DataTransferObject $DTO)
    {
        $resultsByTreeId = $this->getResultsOrderedByTreeId($DTO);
        $TreeObject = $resultsByTreeId[$tree_id] ?? null;
        return $TreeObject;
    }

    private function getDescendants(String $tree_id, DataTransferObject $DTO)
    {
        $treeobject = $this->getTree($tree_id, $DTO);
        if (!($treeobject)) {
            return -1;
        }
        return $treeobject['descendants'];
    }

    private function getRemainingMainTreesToAdd(DataTransferObject $DTO)
    {
        $limit = $this->getMaxTreesPerKeyword() - count($this->getMainTreeList($DTO));
        if ($limit < 0) {
            return 0;
        }
        return $limit;
    }

    private function getRemainingWishListToAdd(DataTransferObject $DTO)
    {
        $limit = $this->getMaxWishList() - count($this->getWishList($DTO));
        if ($limit < 0) {
            return 0;
        }
        return $limit;
    }

    private function getTreeIdDepth(string $tree_id, array $WishListType, int $level, DataTransferObject $DTO, array &$result)
    {
        $level++;
        foreach ($WishListType as $local_tree_id => $treedata) {
            $descendants = $treedata['descendants']??[];
            if (in_array($tree_id, $descendants)) {
                $type = $treedata['type'];
                if ($type) {
                    array_push($result,['tree_id' => $local_tree_id, 'level' =>$level]);
                    return;
                } else {
                    $this->getTreeIdDepth($local_tree_id, $WishListType, $level, $DTO, $result);
                }
            }
        }
    }

}
