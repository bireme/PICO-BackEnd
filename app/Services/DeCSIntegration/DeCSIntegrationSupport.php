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

    protected function ExploreSecondaryTrees(ServicePerformanceSV $ServicePerformance, array $PrimaryMainTrees, DataTransferObject $DTO, $Log)
    {
        $res=[];
        foreach($PrimaryMainTrees as $item){
            $res[$item['key']]=$item['langs'];
        }
        $keyword = $this->getKeyword($DTO);
        $title = '[Keyword: ' . $keyword . ']';
        UltraLoggerFacade::MapArrayIntoUltraLogger($Log, $title . '. Exploring these Main Primary Trees', ['TreesToExplore' => $res], 1);


        $MaxLoops = $this->getMaxExploringLoops() ?? null;
        if (is_int($MaxLoops)) {
            if ($MaxLoops > 10) {
                $MaxLoops = 10;
            }
        } else {
            $MaxLoops = 10;
        }


        $LogData = UltraLoggerFacade::UltraLoggerAttempt($Log, 'Looping (Max Attempts ' . $MaxLoops . ') for secondary trees of keyword: ' . $keyword);
        $blocked=false;
        $countx=0;
        $this->ExploreSecondaryTreesLooper($ServicePerformance, $PrimaryMainTrees, $DTO, $keyword, $MaxLoops, $Log,$countx,$blocked);
        if ($blocked) {
            $summary = 'Finished: Interrumpted. (' . $MaxLoops . '/' . $MaxLoops . ' Loops)';
        } else {
            $summary = 'Finished After ' . $countx . 'loops (Max ' . $MaxLoops . ')';
        }
        UltraLoggerFacade::UltraLoggerSuccessfulAttempt($Log, $LogData, $summary);


        $TreesToExplore = $this->getExploreList($DTO);
        if (count($TreesToExplore)) {
            $res=[];
            foreach($TreesToExplore as $item){
                $res[$item['key']]=$item['langs'];
            }
            UltraLoggerFacade::MapArrayIntoUltraLogger($Log, $title . '. These Remaining Trees were not explored', ['TreesToExplore' => $res], 1);
        } else {
            UltraLoggerFacade::InfoToUltraLogger($Log, $title . '. All trees were explored');
        }
    }

    private function ExploreSecondaryTreesLooper(ServicePerformanceSV $ServicePerformance, array $MainPrimaryTrees, DataTransferObject $DTO, string $keyword, int $MaxLoops,UltraLoggerDevice $Log,int &$countx,bool &$blocked){
        $TreesWithUnexploredLanguages=$MainPrimaryTrees;
        while (true) {
            $countx++;
            if ($countx > $MaxLoops) {
                $blocked = true;
                break;
            }
            $currentTreeToExplore = $TreesWithUnexploredLanguages[0];
            $tree_id=$currentTreeToExplore['key'];
            $langs = $currentTreeToExplore['langs'];
            $LogTwo = UltraLoggerFacade::UltraLoggerAttempt($Log, '[kw= ' . $keyword . '] Exploring Secondary tree_id:' . $tree_id.' ('.json_encode($langs).') '. '... Loop #' . $countx . '(Max ' . $MaxLoops . ')');
            $this->Explore($ServicePerformance, $tree_id, false, $DTO, $Log,$langs); //Antes esto terminaba en false
            $TreesWithUnexploredLanguages = $this->getExploreList($DTO);
            if (!(is_array($TreesWithUnexploredLanguages))) {
                UltraLoggerFacade::ErrorToUltraLogger($Log, 'TreesToExplore Is Not Array');
                throw new DeCSExploringError(['Error' => 'TreesToExploreIsNotArray', 'TreesWithUnexploredLanguages' => $TreesWithUnexploredLanguages]);
            } else {
                if (count($TreesWithUnexploredLanguages) === 0) {
                    UltraLoggerFacade::UltraLoggerSuccessfulAttempt($Log, $LogTwo, 'There were not any more Trees to Explore. Done!');
                    break;
                }else{
                    UltraLoggerFacade::UltraLoggerSuccessfulAttempt($Log, $LogTwo);
                }
            }
        }
        return $countx;
    }

    abstract protected function Explore(ServicePerformanceSV $ServicePerformance, string $key, bool $IsMainTree, DataTransferObject $DTO, UltraLoggerDevice $Log, array $langs);

}
