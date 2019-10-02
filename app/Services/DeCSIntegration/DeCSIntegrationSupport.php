<?php

namespace PICOExplorer\Services\DeCSIntegration;

use PICOExplorer\Exceptions\Exceptions\AppError\DeCSExploringError;
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
        $MainTreesToExplore = $this->getExploredProcessed($DTO,$Log,true);
        if(!($MainTreesToExplore)){
            UltraLoggerFacade::InfoToUltraLogger($Log, $title . '. All the languages of MainTrees were already explored');
        }else{
            foreach ($MainTreesToExplore as $itemArray) {
                $tree_id = $itemArray['key'];
                $langs = $itemArray['langs'];
                $this->Explore($ServicePerformance, $tree_id, -1, $DTO, $Log, $langs);
            }
        }
    }

    protected function ExploreSecondaryTrees(ServicePerformanceSV $ServicePerformance, DataTransferObject $DTO, $Log)
    {
        $title=$this->getTitle($DTO);
        $MaxLoops=$this->getMaxExploringLoops();
        $blocked = false;
        $countx = 0;
        $this->ExploreSecondaryTreesLooper($ServicePerformance, $DTO, $title, $MaxLoops, $Log, $countx, $blocked);
        $this->SummarySecondaryTreesExploring($DTO, $Log, $title, $blocked, $MaxLoops, $countx);
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
            $TreesWithUnexploredLanguages = $this->getExploredProcessed($DTO, $Log, false);
            if (!($TreesWithUnexploredLanguages)) {
                UltraLoggerFacade::InfoToUltraLogger($Log, 'There are no trees left to explore. Done!');
                break;
            }
            $currentTreeToExplore = $TreesWithUnexploredLanguages[0];
            $tree_id = $currentTreeToExplore['key'];
            $langs = $currentTreeToExplore['langs'];
            $subtitle = '. Loop #' . $countx . ': ' . $tree_id;
            UltraLoggerFacade::InfoToUltraLogger($Log, $title . $subtitle);
            $this->Explore($ServicePerformance, $tree_id, false, $DTO, $Log, $langs); //Antes esto terminaba en false
        }
        return $countx;
    }

    private function getExploredProcessed(DataTransferObject $DTO, UltraLoggerDevice $Log, bool $onlyMain)
    {
        $TreesToExplore = $this->getExploreList($DTO, $onlyMain);
        if ($TreesToExplore===null || (!(is_array($TreesToExplore)))) {
            UltraLoggerFacade::ErrorToUltraLogger($Log, 'TreesToExplore Is Not Array');
            throw new DeCSExploringError(['Error' => 'TreesToExploreIsNotArray', 'ExploredTrees' => $TreesToExplore]);
        } elseif (count($TreesToExplore) === 0) {
            return null;
        }
        return $TreesToExplore;
    }

    private function SummarySecondaryTreesExploring(DataTransferObject $DTO, UltraLoggerDevice $Log, String $title, bool $blocked, int $MaxLoops, int $countx)
    {
        $TreesToExplore = $this->getExploredProcessed($DTO, $Log, false);
        if ($blocked) {
            $summary = '. Finished: Interrumpted. (' . $MaxLoops . '/' . $MaxLoops . ' Loops)';
        } else {
            $summary = '. Finished After ' . $countx . 'loops (Max ' . $MaxLoops . ')';
        }
        UltraLoggerFacade::InfoToUltraLogger($Log, $title . $summary);
        if ($TreesToExplore) {
            $res = [];
            foreach ($TreesToExplore as $item) {
                $res[$item['key']] = $item['langs'];
            }
            UltraLoggerFacade::MapArrayIntoUltraLogger($Log, $title . '. These Remaining Trees were not explored', ['TreesToExplore' => $res], 1);
        } else {
            UltraLoggerFacade::InfoToUltraLogger($Log, $title . '. All trees were explored');
        }
    }

    private function getTitle(DataTransferObject $DTO)
    {
        $keyword = $this->getKeyword($DTO);
        $title = '[Keyword: ' . $keyword . '] ';
        return $title;
    }

}
