<?php

namespace PICOExplorer\Services\DeCSIntegration;

use PICOExplorer\Facades\UltraLoggerFacade;
use PICOExplorer\Models\DataTransferObject;
use ServicePerformanceSV;
use PICOExplorer\Services\AdvancedLogger\Services\UltraLoggerDevice;

abstract class DeCSIntegrationSupport extends DeCSIntegrationDataProcessor
{

    protected function ExploreMainTrees(ServicePerformanceSV $ServicePerformance, DataTransferObject $DTO, UltraLoggerDevice $Log)
    {
        $keyword = $this->getKeyword($DTO);
        $langs = $this->getAllValidLanguages();
        $this->Explore($ServicePerformance, $keyword, true, $DTO, $Log, $langs);
    }

    protected function ExploreLanguagesOfMainTrees(ServicePerformanceSV $ServicePerformance, DataTransferObject $DTO, UltraLoggerDevice $Log)
    {
        $title = $this->getTitle($DTO);
        $MainTreeList =$this->getMainTreeList($DTO);
        $results = $this->getResultsOrderedByTreeId($DTO);
        $countx=0;
        foreach ($MainTreeList as $tree_id) {
            $langs=$results[$tree_id]['remaininglangs'];
            if(count($langs)===0){
                continue;
            }
            $this->Explore($ServicePerformance, $tree_id, -1, $DTO, $Log, $langs);
            $countx++;
        }
        if($countx===0){
            UltraLoggerFacade::InfoToUltraLogger($Log, $title . '. All the languages of MainTrees were already explored');
        }
    }

    protected function ExploreSecondaryTrees(ServicePerformanceSV $ServicePerformance, DataTransferObject $DTO, $Log)
    {
        $title=$this->getTitle($DTO);
        $MaxLoops=$this->getMaxExploringLoops();
        $blocked = false;
        $countx = 0;
        $this->ExploreSecondaryTreesLooper($ServicePerformance, $DTO, $title, $MaxLoops, $Log, $countx, $blocked);
    }

    abstract protected function Explore(ServicePerformanceSV $ServicePerformance, string $key, int $IsMainTree, DataTransferObject $DTO, UltraLoggerDevice $Log, array $langs);

    /////////////////////////////////////////
    /// INNER FUNCTIONS
    ////////////////////////////////////////

    private function ExploreSecondaryTreesLooper(ServicePerformanceSV $ServicePerformance, DataTransferObject $DTO, String $title, int $MaxLoops, UltraLoggerDevice $Log, int &$countx, bool &$blocked)
    {
        while (true) {
            $countx++;
            if ($countx > $MaxLoops) {
                $blocked = true;
                break;
            }
            $TreeToExplore = $this->getSecondaryTreeToExplore($DTO);
            if ($TreeToExplore===null) {
                UltraLoggerFacade::InfoToUltraLogger($Log, 'There are no trees left to explore. Done!');
                break;
            }
            $tree_id = $TreeToExplore['key'];
            $langs = $TreeToExplore['langs'];
            $subtitle = '. Loop #' . $countx . ': ' . $tree_id;
            UltraLoggerFacade::InfoToUltraLogger($Log, $title . $subtitle);
            $this->Explore($ServicePerformance, $tree_id, false, $DTO, $Log, $langs); //Antes esto terminaba en false
        }
        return $countx;
    }

    private function getTitle(DataTransferObject $DTO)
    {
        $keyword = $this->getKeyword($DTO);
        $title = '[Keyword: ' . $keyword . '] ';
        return $title;
    }

}
