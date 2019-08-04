<?php

namespace PICOExplorer\Services\DeCSIntegration;

use PICOExplorer\Services\ServiceModels\PICOServiceEntryPoint;

class DeCSBireme extends DeCSExplorer implements PICOServiceEntryPoint
{

    protected $attributes = [
        'AllLangs' => ['en', 'pt', 'es'],
        'MaxExploringLoops' => 5,
        'MaxDescendantsDepth' => 5,
        'MaxDirectDescendantsPerTrees' => 10,
        'MaxTreesPerKeyword' => 10,
    ];

    final public function Process()
    {
        $this->setModelVars();
        $keyword = $this->model->InitialData['keyword'];
        $keyword=str_replace('  ',' ',$keyword);
        $keyword=trim($keyword);
        $keyword=str_replace(' ','+',$keyword);
        //Explore MainTree
        $this->ExploreTreeId($keyword, true);;
        $ToExplore = $this->CheckLangsOfEachResultsTreeId();
        $this->ExploreUnexploredLanguagesPerTreeId($ToExplore);
        // Explore secondary trees
        $countx = 1;
        while (true) {
            if ($countx > ($this->attributes['MaxExploringLoops'])){
                break;
            }
            $TreesToExplore = $this->getTreesToExplore();
            if (count($TreesToExplore) == 0) {
                break;
            }
            $ExploreInfo = current((Array)$TreesToExplore);
            $this->ExploreTreeId($ExploreInfo, false); //Antes esto terminaba en false
            $countx++;
        }
        $ResultsByTerm = $this->model->getResults();
        $this->setResults(__METHOD__ . '@' . get_class($this), $ResultsByTerm);
    }

    ///////////////////////////////////////////////////////////////////
    //INNER FUNCTIONS
    ///////////////////////////////////////////////////////////////////

    private function setModelVars()
    {
        $this->model->setAttribute('AllTrees', []);
        $this->model->setAttribute('ExploredTrees', []);
        $this->model->setAttribute('PendingMainTrees', []);
        $this->model->setAttribute('PendingDescendants', []);
        $this->model->setAttribute('MainTreeList', []);
    }

    private function getTreesToExplore()
    {
        $PriorityArr = array_intersect($this->model->PendingDescendants, $this->model->PendingMainTrees);
        $OrderedOperationArr = array_merge($this->model->PendingDescendants, $this->model->PendingMainTrees);
        $RefinedOperationrArr = array_unique(array_merge($PriorityArr, $OrderedOperationArr));
        $TreesToExplore = array();
        foreach ($RefinedOperationrArr as $tree_id) {
            array_push($TreesToExplore, $tree_id);
        }
        return $TreesToExplore;
    }

    private function CheckLangsOfEachResultsTreeId()
    {
        $ToExplore = [];
        $MainTrees = $this->model->MainTreeList;
        foreach ($MainTrees as $tree_id => $TreeObject) {
            $ExploredLanguages = $TreeObject->getExploredLanguages();
            $RemainingLanguages = array_diff($this->model->InitialData['langs'], $ExploredLanguages);
            $ToExplore[$tree_id] = $RemainingLanguages;
        }
        return $ToExplore;
    }

    private function ExploreUnexploredLanguagesPerTreeId(array $ToExplore)
    {
        foreach ($ToExplore as $tree_id => $RemainingLanguagesArr) {
            if (count($RemainingLanguagesArr) == 0) {
                continue;
            }
            $this->ExploreTreeId($tree_id, false, $RemainingLanguagesArr);
        }
    }

}
