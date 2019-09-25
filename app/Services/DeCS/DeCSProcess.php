<?php

namespace PICOExplorer\Services\DeCS;

use PICOExplorer\Facades\DeCSLooperFacade;
use PICOExplorer\Models\DataTransferObject;
use PICOExplorer\Services\ServiceModels\ServiceEntryPointInterface;
use PICOExplorer\Services\ServiceModels\ToParallelServiceIntegrationTrait;
use ServicePerformanceSV;

class DeCSProcess extends DeCSInfoProcessor implements ServiceEntryPointInterface
{

    use ToParallelServiceIntegrationTrait;

    protected final function Process(ServicePerformanceSV $ServicePerformance, DataTransferObject $DTO, $InitialData)
    {
        //return;
        $InitialData = $DTO->getInitialData();
        $this->DecodePreviousData($DTO, $InitialData['SavedData'] ?? null);
        $QueryProcessed = $this->ProcessInitialQuery($InitialData['query'],$InitialData['ImprovedSearch']??null);
        $this->BuildKeywordList($DTO,$QueryProcessed,$InitialData['langs']);
        $this->Explore($ServicePerformance, $DTO, $InitialData['mainLanguage']);
        $this->BuildDeCSHTML($DTO, $InitialData['PICOnum']);
        $results = [
            'SavedData' => json_encode($DTO->getAttr('SavedData')),
            'DescriptorsHTML' => $DTO->getAttr('DescriptorsHTML'),
            'DeCSHTML' => $DTO->getAttr('DeCSHTML'),
        ];
        return $results;
    }

    protected function Explore(ServicePerformanceSV $ServicePerformance, DataTransferObject $DTO, string $mainLanguage)
    {
        $IntegrationResults = [];
        $KeywordList = $DTO->getAttr('KeywordList');
        //dd($KeywordList);
        foreach ($KeywordList as  $keyword => $langdata) {
            $langs = array_values($langdata);
            if(count($langs)===0){
                continue;
            }
            $IntegrationResults[$keyword] = $this->Connect($ServicePerformance, $keyword,$langs);
        }
        $this->MixWithOldData($DTO, $IntegrationResults);
        $this->BuildAsTerms($DTO, $mainLanguage);
    }


    ///////////////////////////////////////////////////////////////////
    //INNER FUNCTIONS
    ///////////////////////////////////////////////////////////////////


    private final function Connect(ServicePerformanceSV $ServicePerformance, string $keyword, array $langs)
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
