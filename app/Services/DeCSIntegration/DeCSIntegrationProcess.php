<?php

namespace PICOExplorer\Services\DeCSIntegration;

use PICOExplorer\Exceptions\Exceptions\AppError\DeCSExploringError;
use PICOExplorer\Facades\DeCSIntegrationLooperFacade;
use PICOExplorer\Facades\UltraLoggerFacade;
use PICOExplorer\Models\DataTransferObject;
use PICOExplorer\Services\AdvancedLogger\Services\UltraLoggerDevice;
use PICOExplorer\Services\AdvancedLogger\Exceptions\DontCatchException;
use PICOExplorer\Services\ServiceModels\ServiceEntryPointInterface;
use PICOExplorer\Services\ServiceModels\ToParallelServiceIntegrationTrait;
use ServicePerformanceSV;

class DeCSIntegrationProcess extends DeCSIntegrationSupport implements ServiceEntryPointInterface
{

    use ToParallelServiceIntegrationTrait;

    protected final function Process(ServicePerformanceSV $ServicePerformance, DataTransferObject $DTO, $InitialData)
    {
        $Log = null;
        $results = 'Unset';
        $wasSuccesful=false;
        try {
            $Log = UltraLoggerFacade::createUltraLogger('DeCSIntegrationProcess',$InitialData);
            $LogData = UltraLoggerFacade::UltraLoggerAttempt($Log, 'Step 1/3. Setting initial vars');
            $this->setInitialVars($DTO);
            UltraLoggerFacade::UltraLoggerSuccessfulAttempt($Log, $LogData);

            $LogData = UltraLoggerFacade::UltraLoggerAttempt($Log, 'Step 2/3. Primary Main Trees');
            $this->ExploreMainTrees($ServicePerformance, $DTO,$Log);
            UltraLoggerFacade::UltraLoggerSuccessfulAttempt($Log, $LogData);

            $TreesToExplore = $this->getExploreList($DTO);
            if (!(is_array($TreesToExplore))) {
                UltraLoggerFacade::ErrorToUltraLogger($Log, 'MainTrees Is Not Array');
                throw new DeCSExploringError(['Error' => 'TreesToExploreIsNotArray', 'TreesToExplore' => $TreesToExplore]);
            } else {
                if (count($TreesToExplore) === 0) {
                    UltraLoggerFacade::WarningToUltraLogger($Log, 'No tree_ids were retrieved from BIREME. Step 3 Skipped');
                    $results = 'no-res';
                } else {
                    $LogData = UltraLoggerFacade::UltraLoggerAttempt($Log, 'Step 3/3. Exploring secondary trees');
                    $this->ExploreSecondaryTrees($ServicePerformance,$TreesToExplore, $DTO, $Log);
                    UltraLoggerFacade::UltraLoggerSuccessfulAttempt($Log, $LogData);
                }
            }

            $results = $this->getResultsOrderedByTreeId($DTO);
            if(count($results)===0){
                $results= 'no-res';
            }
            $wasSuccesful=true;
        } catch (DontCatchException $ex) {
            //
        } finally {
            if($results==='no-res'){
                UltraLoggerFacade::WarningToUltraLogger($Log, 'No results built in this service. Returning "no-res"');
            }
            if ($Log) {
                UltraLoggerFacade::saveUltraLoggerResults($Log,$wasSuccesful, true);
            }
            return $results;
        }
    }

    ///////////////////////////////////////////////////////////////////
    //INNER FUNCTIONS
    //////////////////////////////////////////////////////////////////

    protected function Explore(ServicePerformanceSV $ServicePerformance, string $key, bool $IsMainTree, DataTransferObject $DTO, UltraLoggerDevice $Log, array $langs)
    {
        $keyword = $this->getKeyword($DTO);
        $title = '[kw= ' . $keyword . '] Exploring languages: '. json_encode($langs);
        if ($IsMainTree) {
            $title = $title . 'words= ' . $key;
        } else {
            $title = $title . 'tree_id= ' . $key;
        }

        $LogData = UltraLoggerFacade::UltraLoggerAttempt($Log, $title);
        $results=[];
        foreach ($langs as $lang) {
            $FirstArgument = 'tree_id';
            if ($IsMainTree == true) {
                $FirstArgument = 'words';
            }
            $data = [
                $FirstArgument => $key,
                'lang' => $lang,
            ];
            $results[$lang] = $this->Connect($ServicePerformance, $data,$Log);
        }
        UltraLoggerFacade::UltraLoggerSuccessfulAttempt($Log, $LogData);


        $LogData = UltraLoggerFacade::UltraLoggerAttempt($Log, $title);
        $this->ProcessImportResults($results,$IsMainTree,$DTO,$title,$Log);
        UltraLoggerFacade::UltraLoggerSuccessfulAttempt($Log, $LogData);
    }

    ///////////////////////////////////////////////////////////////////
    //INNER FUNCTIONS
    ///////////////////////////////////////////////////////////////////

    private final function Connect(ServicePerformanceSV $ServicePerformance, $data,$Log)
    {
        $AdvancedFacade = new DeCSIntegrationLooperFacade();
        $XMLresults = $this->ToParallelServiceIntegration($ServicePerformance, $AdvancedFacade, $data);
        if($XMLresults==='no-res'){
            $XMLresults=[];
        }
        return $XMLresults;
    }

}
