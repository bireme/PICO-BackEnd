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
        'MaxDescendantsDepth' => 5,
        'AddDescendantsToMainTrees' => true,
        'AddRelatedTreesToMainTrees' => true,
        'AllValidLanguages'=>[
            'en',
            'es',
            'pt'
        ]
    ];

    protected function getAllValidLanguages()
    {
        return $this->controllerData['AllValidLanguages'];
    }

    protected function getMaxExploringLoops()
    {
        return $this->controllerData['MaxExploringLoops'];
    }

    protected function getMaxTreesPerKeyword()
    {
        return $this->controllerData['MaxTreesPerKeyword'];
    }

    protected function getMaxDescendantsPerTree()
    {
        return $this->controllerData['MaxDescendantsPerTree'];
    }

    protected function getMaxDescendantsDepth()
    {
        return $this->controllerData['MaxDescendantsDepth'];
    }

    protected function ShouldIAddDescendantsToMainTrees()
    {
        return $this->controllerData['AddDescendantsToMainTrees'];
    }

    protected function ShouldIAddRelatedTreesToMainTrees()
    {
        return $this->controllerData['AddRelatedTreesToMainTrees'];
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
            'log' => '',
            'logcount' => 0,
            'langs' => $InitialData['langs'],
            'keyword' => $keyword,
            'PendingMainTrees' => [],
            'PendingDescendants' => [],
            'MainTreeList' => [],
            'ExploredTrees' => [],
            'TmpResults' => [],
        ];

        $DTO->SaveToModel(get_class($this), $inidata);
        $this->addLogData('Using Controllerdata:', $this->controllerData,$DTO);
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
    /// LOG FUNCTIONS
    ////////////////////////////////////////////////////////

    protected function addLogLine(string $txt,DataTransferObject $DTO)
    {
        $logcount = $DTO->getAttr('logcount') + 1;
        $log = $DTO->getAttr('log');
        $log = '[' . $logcount . ']' . $log . $txt . PHP_EOL;
        $DTO->SaveToModel(get_class($this), ['log' => $log, 'logcount' => $logcount]);
    }

    protected function addLogData(string $description, $data,DataTransferObject $DTO)
    {
        $datatxt = json_encode($data);
        $txt = $description . ' => ' . $datatxt;
        $this->addLogLine($txt,$DTO);
    }

    protected function addLogError(string $ErrorDescription, $data,DataTransferObject $DTO)
    {
        $description = '[Error] ' . $ErrorDescription;
        $this->addLogData($description, $data,$DTO);
    }

    protected function getLog(DataTransferObject $DTO)
    {
        return $DTO->getAttr('log');
    }

    private function Summary(DataTransferObject $DTO)
    {
        $MainTrees = $this->getMainTreeList($DTO);
        $txt = count($MainTrees) . ' Main Trees: ' . json_encode($MainTrees);
    }

    ////////////////////////////////////////////////////////
    /// Getters of trees
    ////////////////////////////////////////////////////////

    protected function getMainTreeList(DataTransferObject $DTO)
    {
        return $DTO->getAttr('MainTreeList');
    }

    protected function getExploredMainTrees(DataTransferObject $DTO)
    {
        $explored = $DTO->getAttr('ExploredTrees');
        $maintrees = $DTO->getAttr('MainTreeList');
        return array_diff($maintrees, $explored);
    }

    protected function ExploredTrees(DataTransferObject $DTO)
    {
        return $DTO->getAttr('ExploredTrees');
    }

    protected function getUnexploredTrees(DataTransferObject $DTO)
    {
        $maintrees = $DTO->getAttr('PendingMainTrees');
        $descendants = $DTO->getAttr('PendingDescendants');
        return array_unique(array_merge($maintrees, $descendants));
    }

    protected function addToMainTreeList(array $tree_ids,DataTransferObject $DTO)
    {
        $maintrees = $DTO->getAttr('MainTreeList');
        $maintrees = array_unique(array_merge($maintrees, $tree_ids));
        $DTO->SaveToModel(get_class($this), ['MainTreeList' => $maintrees]);
    }

    protected function addToUnexploredMainTrees(array $tree_ids,DataTransferObject $DTO)
    {
        $maintrees = $DTO->getAttr('PendingMainTrees');
        $maintrees = array_unique(array_merge($maintrees, $tree_ids));
        $DTO->SaveToModel(get_class($this), ['PendingMainTrees' => $maintrees]);
    }

    protected function addToUnexploredDescendants(array $tree_ids,DataTransferObject $DTO)
    {
        $descendants = $DTO->getAttr('PendingDescendants');
        $descendants = array_unique(array_merge($descendants, $tree_ids));
        $DTO->SaveToModel(get_class($this), ['PendingDescendants' => $descendants]);
    }

    protected function addToExploredTrees(string $tree_id,DataTransferObject $DTO)
    {
        $ExploredTrees = $DTO->getAttr('ExploredTrees');
        array_push($ExploredTrees, $tree_id);
        $DTO->SaveToModel(get_class($this), ['ExploredTrees' => $ExploredTrees]);
    }

    ////////////////////////////////////////////////////////
    /// Getters of DTO
    ////////////////////////////////////////////////////////

    protected function getDataByTreeId(DataTransferObject $DTO)
    {
        return $DTO->getAttr('TmpResults');
    }

    protected function AddDataToResults(string $tree_id, string $lang, string $term, array $decs, array $descendants,DataTransferObject $DTO)
    {
        $ResultsByTreeId = $this->getDataByTreeId($DTO);
        if (!(array_key_exists($tree_id, $ResultsByTreeId))) {
            $ResultsByTreeId[$tree_id] = [];
        }
        if (!(array_key_exists('descendants', $ResultsByTreeId[$tree_id]))) {
            $this->addLogData('Adding descendants...', $descendants,$DTO);
            $ResultsByTreeId[$tree_id]['descendants'] = $descendants;
        } else {
            $descendants = array_diff($descendants, $ResultsByTreeId[$tree_id]['descendants']);
            if (count($descendants)) {
                $this->addLogData('Adding descendants...', $descendants,$DTO);
                $ResultsByTreeId[$tree_id]['descendants'] = array_merge($ResultsByTreeId[$tree_id]['descendants'], $descendants);
            }
        }
        $data = [
            'term' => $term,
            'decs' => $decs,
        ];
        if (!(array_key_exists($lang, $ResultsByTreeId[$tree_id]))) {
            $ResultsByTreeId[$tree_id][$lang] = $data;
            $DTO->SaveToModel(get_class($this), ['TmpResults' => $ResultsByTreeId]);
            $this->addLogData('Adding results...', $data,$DTO);
        } else {
            $this->addLogError('Attempted to add data to already explored lang (' . $lang . ') of tree_id(' . $tree_id . ')', $data,$DTO);
        }
    }

}
