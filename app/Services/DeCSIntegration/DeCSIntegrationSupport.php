<?php

namespace PICOExplorer\Services\DeCSIntegration;

use PICOExplorer\Models\DataTransferObject;
use ServicePerformanceSV;

abstract class DeCSIntegrationSupport extends DeCSIntegrationDataProcessor
{

    protected function ExploreMainTree(ServicePerformanceSV $ServicePerformance, DataTransferObject $DTO)
    {
        $keyword = $this->getKeyword($DTO);
        $langs=$this->getAllValidLanguages();
        $this->ExploreLanguageLooper($ServicePerformance, $keyword, true,$DTO,$langs);
    }

    protected function ExploreSecondaryTrees(ServicePerformanceSV $ServicePerformance, DataTransferObject $DTO)
    {
        $countx = 1;
        while (true) {
            if ($countx > ($this->getMaxExploringLoops())) {
                break;
            }
            $TreesToExplore = $this->getUnexploredTrees($DTO);
            if (count($TreesToExplore) > 0) {
                $currentTreeToExplore = current((Array)$TreesToExplore);
                $this->ExploreLanguageLooper($ServicePerformance, $currentTreeToExplore, false,$DTO); //Antes esto terminaba en false
                $countx++;
            }
        }
    }

    protected function ExploreUnexploredLanguagesPerTreeId(ServicePerformanceSV $ServicePerformance, DataTransferObject $DTO)
    {
        $TreesWithUnexploredLanguages = $this->getTreesWithUnexploredLanguages($DTO);
        foreach ($TreesWithUnexploredLanguages as $tree_id => $RemainingLanguagesArr) {
            if (count($RemainingLanguagesArr) == 0) {
                continue;
            }
            $this->ExploreLanguageLooper($ServicePerformance, $tree_id, false, $DTO, $RemainingLanguagesArr);
        }
    }

    private function ExploreLanguageLooper(ServicePerformanceSV $ServicePerformance, string $key, bool $IsMainTree, DataTransferObject $DTO,array $langs = NULL, bool $reExploringMainTrees=false)
    {
        if (!($langs)) {
            $langs = $this->getLangs($DTO);
        }
        foreach ($langs as $lang) {
            $results[$lang] = $this->Explore($ServicePerformance, $key, $lang, $IsMainTree,$reExploringMainTrees, $DTO);
        }
    }

    abstract protected function Explore(ServicePerformanceSV $ServicePerformance,string $key, string $lang, bool $IsMainTree,bool $reExploringMainTrees,DataTransferObject $DTO);

}
