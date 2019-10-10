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
        'MinTreesNotCheckDescendants' => 20,
        'MaxTrees' => 35,
        'MaxDescendantsCheckedByDepth' => 30,
        'MaxSecondaryLoopsSecurity' => 40,
        'AllValidLanguages' => [
            'en',
            'es',
            'pt'
        ]
    ];


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
            'ResultsByTreeId' => [],
            'FinalResults' => [],
            'LevelList' => [],
            'TotalCount' => 0,
            'Queue' => [],
            'TotalList' => [],
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


    /////////////////////////////////////////////////////
    /// GLOBAL LIMITERS
    ////////////////////////////////////////////////////////

    protected function getSecondaryTreeToExplore(DataTransferObject $DTO)
    {
        $list = $DTO->getAttr('Queue');
        if (count($list) === 0) {
            return null;
        }
        $results = $this->getResultsOrderedByTreeId($DTO);
        $key = $list[0];
        $RemaningLangs = $results[$key]['remaininglangs'] ?? null;
        if ($RemaningLangs === null) {
            $langs = $this->getLangs($DTO);
        } elseif (count($RemaningLangs)) {
            $langs = $RemaningLangs;
        } else {
            return null;
        }
        return [
            'key' => $key,
            'langs' => $langs
        ];
    }


    protected function addToMainTreeList(array $tree_ids, DataTransferObject $DTO)
    {
        $remainingmaintrees = $this->getRemainingMainTreesToAdd($DTO);
        if ($remainingmaintrees) {
            $added = array_slice($tree_ids, 0, $remainingmaintrees, true);
        } else {
            $added = [];
        }
        $cut = array_diff($tree_ids, $added);

        $info = [
            'added' => $added,
            'cut' => $cut,
        ];
        $levellist = [
            0 => $added,
        ];
        $DTO->SaveToModel(get_class($this), ['MainTreeList' => $added, 'LevelList' => $levellist]);
        $this->addToCount($DTO, count($added));
        return $info;
    }

    protected function addToCount(DataTransferObject $DTO, int $num)
    {
        $count = $DTO->getAttr('TotalCount');
        $count = $count + $num;
        $DTO->SaveToModel(get_class($this), ['TotalCount' => $count]);
    }

    protected function AreMaxTreesExceeded(DataTransferObject $DTO)
    {
        $count = $DTO->getAttr('TotalCount');
        $max = $this->controllerData['MaxTrees'];
        if ($count > $max) {
            return true;
        } else {
            return false;
        }
    }

    protected function addToQueue(array $tree_ids, int $depthlevel, DataTransferObject $DTO)
    {
        if ($this->AreMaxTreesExceeded($DTO)) {
            return [
                'added' => [],
                'cut' => $tree_ids,
            ];
        }
        $oldTotal = $DTO->getAttr('TotalList');
        $new_ids = array_diff($tree_ids, $oldTotal);
        $Queue = $DTO->getAttr('Queue');

        $levellist = $DTO->getAttr('LevelList');
        if (($levellist[$depthlevel] ?? null) === null) {
            $levellist[$depthlevel] = [];
        }
        $Queue = array_merge($Queue, $new_ids);
        $levellist[$depthlevel] = array_merge($levellist[$depthlevel], $new_ids);
        $DTO->SaveToModel(get_class($this), ['Queue' => $Queue, 'LevelList' => $levellist]);
        $info = [
            'added' => $new_ids,
        ];
        return $info;
    }

    protected
    function addDescendantsToTree(array $descendants_ids, String $tree_id, int $depthlevel, bool $isMain, DataTransferObject $DTO)
    {
        $maxtrees = $this->controllerData['MaxTrees'];
        $totalcount = $DTO->getAttr('TotalCount');
        if ($totalcount > $maxtrees) {
            $info = [
                'added' => [],
                'cut' => $descendants_ids,
            ];
            return $info;
        }

        if (!($isMain)) {
            $resultsByTreeId = $this->getResultsOrderedByTreeId($DTO);
            $olddescendants = $resultsByTreeId[$tree_id]['descendants'] ?? [];
        } else {
            $olddescendants = [];
        }
        $maxdescendantspertree = $this->controllerData['MaxDescendantsPerTree'];
        $newdescendants = array_diff($descendants_ids, $olddescendants);
        $remainingdescendants = $maxdescendantspertree - count($olddescendants);

        if ($remainingdescendants) {
            $added = array_slice($newdescendants, 0, $remainingdescendants, true);
        } else {
            $added = [];
        }
        if (count($added)) {
            $this->addToQueue($added, $depthlevel, $DTO);
        }

        $cut = array_diff($newdescendants, $added);
        $info = [
            'added' => $added,
            'cut' => $cut,
        ];
        return $info;
    }


    protected
    function IsTreeDepthOk(String $parent_id, DataTransferObject $DTO)
    {
        $LevelList = $DTO->getAttr('LevelList');
        $maxdepth = $this->controllerData['MaxDescendantsDepth'];
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

    private
    function getRemainingMainTreesToAdd(DataTransferObject $DTO)
    {
        $limit = $this->controllerData['MaxTreesPerKeyword'] - count($this->getMainTreeList($DTO));
        if ($limit < 0) {
            return 0;
        }
        return $limit;
    }

}
