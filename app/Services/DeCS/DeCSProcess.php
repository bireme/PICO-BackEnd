<?php

namespace PICOExplorer\Services\DeCS;

use PICOExplorer\Facades\DeCSLooperFacade;
use PICOExplorer\Facades\UltraLoggerFacade;
use PICOExplorer\Models\DataTransferObject;
use PICOExplorer\Services\AdvancedLogger\Exceptions\DontCatchException;
use PICOExplorer\Services\AdvancedLogger\Services\UltraLoggerDevice;
use PICOExplorer\Services\ServiceModels\ServiceEntryPointInterface;
use PICOExplorer\Services\ServiceModels\ToParallelServiceIntegrationTrait;
use ServicePerformanceSV;

class DeCSProcess extends DeCSQueryBuild implements ServiceEntryPointInterface
{

    use ToParallelServiceIntegrationTrait;

    protected final function Process(ServicePerformanceSV $ServicePerformance, DataTransferObject $DTO, $InitialData)
    {
        $Log = null;
        $results = 'Unset';
        $wasSuccesful=false;
        try {
            $InitialData = $DTO->getInitialData();
            $Log = UltraLoggerFacade::createUltraLogger('DeCSProcess', $InitialData);

            $LogData = UltraLoggerFacade::UltraLoggerAttempt($Log, 'Attempting to decode previous data');
            $this->DecodePreviousData($DTO, $InitialData['SavedData'] ?? null);
            UltraLoggerFacade::UltraLoggerSuccessfulAttempt($Log, $LogData);



            $LogData = UltraLoggerFacade::UltraLoggerAttempt($Log, 'Attempting to process query and improved search');
            $QueryProcessed = $this->ProcessInitialQuery($InitialData['query'], $InitialData['ImprovedSearch'] ?? null);
            UltraLoggerFacade::UltraLoggerSuccessfulAttempt($Log, $LogData);

            $LogData = UltraLoggerFacade::UltraLoggerAttempt($Log, 'Attempting to build Lists From proocessed queries');
            $this->BuildListsFromProcessedData($DTO, $QueryProcessed, $InitialData['langs'], $Log);
            UltraLoggerFacade::UltraLoggerSuccessfulAttempt($Log, $LogData);

            $this->Explore($ServicePerformance, $DTO, $InitialData['mainLanguage'], $Log);

            $LogData = UltraLoggerFacade::UltraLoggerAttempt($Log, 'Attempting to build html');
            $this->BuildDeCSHTML($DTO, $InitialData['PICOnum']);
            UltraLoggerFacade::UltraLoggerSuccessfulAttempt($Log, $LogData);
            $results = [
                'SavedData' => json_encode($DTO->getAttr('SavedData')),
                'DescriptorsHTML' => $DTO->getAttr('DescriptorsHTML'),
                'DeCSHTML' => $DTO->getAttr('DeCSHTML'),
            ];
            $wasSuccesful=true;
        } catch (DontCatchException $ex) {
            //
        } finally {
            if ($Log) {
                UltraLoggerFacade::saveUltraLoggerResults($Log,$wasSuccesful, false);
            }
        }
        return $results;

    }

    protected function Explore(ServicePerformanceSV $ServicePerformance, DataTransferObject $DTO, string $mainLanguage, UltraLoggerDevice $Log)
    {

        $KeywordList = $DTO->getAttr('KeywordList');
        UltraLoggerFacade::InfoToUltraLogger($Log, 'Processing KeywordList => Step 1/2 Connections');

        $index=1;
        $IntegrationResults = [];
        foreach ($KeywordList as $keyword => $langdata) {
            $langs = array_values($langdata);
            if (count($langs) === 0) {
                continue;
            }
            $LogData = UltraLoggerFacade::UltraLoggerAttempt($Log, '('.($index+1). '/' .(count($langs). ') Attempting Connection:' . json_encode(['keyword'=>$keyword,'langs'=>$langs])));
            $res = $this->Connect($ServicePerformance, $keyword, $langs, $Log);
            UltraLoggerFacade::UltraLoggerSuccessfulAttempt($Log, $LogData);

            if($res==='no-res'){
                $res=null;
            }
            $IntegrationResults[$keyword] = $res;
            $index++;
        }
        UltraLoggerFacade::InfoToUltraLogger($Log, 'Processing KeywordList => Step 2/3 Mixing Results With OldData');
        $this->MixWithOldData($DTO, $IntegrationResults, $Log);

        UltraLoggerFacade::InfoToUltraLogger($Log, 'Processing KeywordList => Step 3/3 Building as Terms');
        $this->BuildAsTerms($DTO, $mainLanguage, $Log);
    }


    ///////////////////////////////////////////////////////////////////
    //INNER FUNCTIONS
    ///////////////////////////////////////////////////////////////////


    private final function Connect(ServicePerformanceSV $ServicePerformance, string $keyword, array $langs, UltraLoggerDevice $Log)
    {
        $data = [
            'keyword' => $keyword,
            'langs' => $langs,
        ];
        $AdvancedFacade = new DeCSLooperFacade();
        $IntegrationData = $this->ToParallelServiceIntegration($ServicePerformance, $AdvancedFacade, $data);
        return $IntegrationData;
    }


}
