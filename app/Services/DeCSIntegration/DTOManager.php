<?php

namespace PICOExplorer\Services\DeCSIntegration;

use mysql_xdevapi\Exception;
use PICOExplorer\Exceptions\Exceptions\AppError\ErrorInExploredTrees;
use PICOExplorer\Models\DataTransferObject;
use PICOExplorer\Services\ServiceModels\ServiceEntryPoint;
use Symfony\Component\CssSelector\Exception\InternalErrorException;

abstract class DTOManager extends ServiceEntryPoint
{

    ////////////////////////////////////////////////////////
    /// ControllerData
    ////////////////////////////////////////////////////////

    protected $controllerData = [
        'MaxTreesPerKeyword' => 10,
        'MaxDescendantsPerTree' => 30,
        'MaxDescendantsDepth' => 5,
        'SaveDescendantsOfPrimaryMainTreesIntoMainTrees' => true,
        'SaveRelatedTreesOfPrimaryMainTreesIntoMainTrees' => true,
        'MaxSecondaryLoopsSecurity' => 200,
        'AllValidLanguages' => [
            'en',
            'es',
            'pt'
        ]
    ];

    ///
    ///GLOBAL CONTROLLER VARS
    ///

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
        $keyword = str_replace(' ', '*', $keyword);
        $inidata = [
            'langs' => $InitialData['langs'],
            'keyword' => $keyword,
            'MainTreeList' => [],
            'WishList' => [],
            'ResultsByTreeId' => [],
            'FinalResults' => [],
            'LevelList' => [],
        ];
        $DTO->SaveToModel(get_class($this), $inidata);
    }

    protected function getMaxExploringLoops()
    {
        return $this->controllerData['MaxSecondaryLoopsSecurity'];
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

    protected function getLevelList(DataTransferObject $DTO)
    {
        return $DTO->getAttr('LevelList');
    }

    protected function saveResultsOrderedByTreeId(DataTransferObject $DTO, array $ResultsOrderedByTreeId)
    {
        $DTO->SaveToModel(get_class($this), ['ResultsByTreeId' => $ResultsOrderedByTreeId]);
    }

    protected function saveFinalResults(DataTransferObject $DTO, array $FinalResults)
    {
        $DTO->SaveToModel(get_class($this), ['FinalResults' => $FinalResults]);
    }

    protected function getFinalResults(DataTransferObject $DTO)
    {
        return $DTO->getAttr('FinalResults');
    }

    protected function getExploreList(DataTransferObject $DTO, bool $onlyMain)
    {
        $maintrees = $this->getMainTreeList($DTO);
        if (!($onlyMain)) {
            $wishlist = $this->getWishList($DTO);
        } else {
            $wishlist = [];
        }
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
        $DTO->SaveToModel(get_class($this), ['MainTreeList' => array_merge($oldmaintrees, $added)]);
        $depthlevel = 0;
        $this->addToWishList($added, $depthlevel, $DTO);
        return $info;
    }

    protected function addToWishList(array $tree_ids, int $depthlevel, DataTransferObject $DTO)
    {
        $oldwishlist = $this->getWishList($DTO);
        $levellist = $this->getLevelList($DTO);
        if (($levellist[$depthlevel] ?? null) === null) {
            $levellist[$depthlevel] = [];
        }
        $levellist[$depthlevel] = array_unique(array_merge($levellist[$depthlevel], $tree_ids));
        $added = array_diff($tree_ids, $oldwishlist);
        $cut = array_diff($tree_ids, $added);
        $wishlist = array_merge($oldwishlist, $added);
        $DTO->SaveToModel(get_class($this), ['WishList' => $wishlist, 'LevelList' => $levellist]);

        $info = [
            'added' => $added,
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

    protected function addDescendantsToTree(array $descendants_ids, String $tree_id, int $depthlevel, bool $isMain, DataTransferObject $DTO)
    {
        if (!($isMain)) {
            $resultsByTreeId = $this->getResultsOrderedByTreeId($DTO);
            $olddescendants = $resultsByTreeId[$tree_id]['descendants'] ?? [];
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

        $this->addToWishList($added, $depthlevel, $DTO);

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
        $relevant = array_intersect($wishlist, array_keys($exploredTrees));

        $result = [];
        foreach ($relevant as $key => $tree_id) {
            if (in_array($tree_id, $maintrees)) {
                $type = true;
            } else {
                $type = false;
            }
            $TreeObj = $exploredTrees[$tree_id] ?? null;
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


    protected function IsTreeDepthOk(String $parent_id, DataTransferObject $DTO)
    {
        $LevelList = $this->getLevelList($DTO);
        $maxdepth = $this->getMaxDescendantsDepth();
        $current = null;
        foreach ($LevelList as $level => $data) {
            if (in_array($parent_id, $data)) {
                $current = $level;
                break;
            }
        }
        $info = [
            'current' => $current,
            'max' => $maxdepth,
        ];

        return $info;
    }

    ///////////////////////////////////////
    /// INNNER
    /// //////////////////////////////////////

    private function getRemainingMainTreesToAdd(DataTransferObject $DTO)
    {
        $limit = $this->getMaxTreesPerKeyword() - count($this->getMainTreeList($DTO));
        if ($limit < 0) {
            return 0;
        }
        return $limit;
    }

}
