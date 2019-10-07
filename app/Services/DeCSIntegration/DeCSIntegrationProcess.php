<?php

namespace PICOExplorer\Services\DeCSIntegration;

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
        $wasSuccesful = false;
        try {
            $Log = UltraLoggerFacade::createUltraLogger('DeCSIntegrationProcess', $InitialData);
            $LogData = UltraLoggerFacade::UltraLoggerAttempt($Log, 'Step 1/5. Setting initial vars');
            $this->setInitialVars($DTO);
            UltraLoggerFacade::UltraLoggerSuccessfulAttempt($Log, $LogData);

            $LogData = UltraLoggerFacade::UltraLoggerAttempt($Log, 'Step 2/5. Primary Main Trees');
            $this->ExploreMainTrees($ServicePerformance, $DTO, $Log);
            UltraLoggerFacade::UltraLoggerSuccessfulAttempt($Log, $LogData);

            $LogData = UltraLoggerFacade::UltraLoggerAttempt($Log, 'Step 3/5. Exploring other languages in Primary Main Trees');
            $this->ExploreLanguagesOfMainTrees($ServicePerformance, $DTO, $Log);
            UltraLoggerFacade::UltraLoggerSuccessfulAttempt($Log, $LogData);

            $LogData = UltraLoggerFacade::UltraLoggerAttempt($Log, 'Step 4/5. Exploring secondary trees');
            $this->ExploreSecondaryTrees($ServicePerformance, $DTO, $Log);
            UltraLoggerFacade::UltraLoggerSuccessfulAttempt($Log, $LogData);

            $MainTrees = $this->getMainTreeList($DTO);
            $results = $this->getResultsOrderedByTreeId($DTO);
            $ObtainedTreeIds=array_keys($results);
            if (count($results) === 0) {
                $results = 'no-res';
            }
            $InitialData = $DTO->getInitialData();
            UltraLoggerFacade::setSubTitle($Log,$InitialData['keyword'].':'.json_encode($InitialData['langs']));
            UltraLoggerFacade::setSubTitle($Log,count($MainTrees).' MainTrees ('.count($ObtainedTreeIds).' Total)');
            $wasSuccesful = true;
        } catch (DontCatchException $ex) {
            //
        } finally {
            if ($results === 'no-res') {
                UltraLoggerFacade::WarningToUltraLogger($Log, 'No results built in this service. Returning "no-res"');
            }
            if ($Log) {
                UltraLoggerFacade::saveUltraLoggerResults($Log, $wasSuccesful, true);
            }
        }
        return $results;
    }

    ///////////////////////////////////////////////////////////////////
    //INNER FUNCTIONS
    //////////////////////////////////////////////////////////////////

    protected function Explore(ServicePerformanceSV $ServicePerformance, string $key, int $TreeType, DataTransferObject $DTO, UltraLoggerDevice $Log, array $langs)
    {

        $FirstArgument = 'tree_id';
        if ($TreeType == 1) {
            $FirstArgument = 'words';
        }

        $title = '[' . $FirstArgument . '=' . $key . ' langs=' . json_encode($langs).']. ';
        UltraLoggerFacade::InfoToUltraLogger($Log, $title.'Step 1/2 Connections');
        $results = [];
        foreach ($langs as $index => $lang) {
            $data = [
                $FirstArgument => $key,
                'lang' => $lang,
            ];
            $LogData = UltraLoggerFacade::UltraLoggerAttempt($Log, '('.($index+1). '/' .(count($langs). ') Attempting Connection:' . json_encode($data)));
            $results[$lang] = $this->Connect($ServicePerformance, $data, $Log);
            UltraLoggerFacade::UltraLoggerSuccessfulAttempt($Log, $LogData);
        }

        $LogData = UltraLoggerFacade::UltraLoggerAttempt($Log, $title . 'Step 2/2 Processing results');
        if($TreeType===1 || $TreeType===-1){
            $isMain=true;
        }else{
            $isMain=false;
        }

        $this->ProcessImportResults($results, $isMain, $DTO, $Log);
        UltraLoggerFacade::InfoToUltraLogger($Log, '--------------');
        UltraLoggerFacade::UltraLoggerSuccessfulAttempt($Log, $LogData);
    }

    ///////////////////////////////////////////////////////////////////
    //INNER FUNCTIONS
    ///////////////////////////////////////////////////////////////////

    private final function Connect(ServicePerformanceSV $ServicePerformance, $data, $Log)
    {
        $AdvancedFacade = new DeCSIntegrationLooperFacade();
        $XMLresults = $this->ToParallelServiceIntegration($ServicePerformance, $AdvancedFacade, $data);
        if ($XMLresults === 'no-res') {
            $XMLresults = [];
        }
        return $XMLresults;
    }

}
