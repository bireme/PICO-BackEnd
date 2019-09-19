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
        $InitialData= $DTO->getInitialData();
        $this->DecodePreviousData($DTO,$InitialData['SavedData']);
        $PreviousData = $DTO->getAttr('PreviousData');
        $this->BuildKeywordList($DTO,$InitialData['query'],$PreviousData,$InitialData['langs']);
        $this->Explore($ServicePerformance,$DTO,$InitialData['mainLanguage'],$InitialData['langs']);
        $this->BuildHTML($DTO,$InitialData['PICOnum']);
        $results = [
            'QuerySplit' => json_encode($DTO->getAttr('QuerySplit')),
            'SavedData' => json_encode($DTO->getAttr('ProcessedResults')),
            'DescriptorsHTML' => $DTO->getAttr('DescriptorsHTML'),
            'DeCSHTML' =>$DTO->getAttr('DeCSHTML'),
        ];
        return $results;
    }

    protected function Explore(ServicePerformanceSV $ServicePerformance, DataTransferObject $DTO,string $mainLanguage,array $langArr)
    {
        $IntegrationResults = [];
        $KeywordList = $DTO->getAttr('KeywordList')??[];
        foreach ($KeywordList as $keyword => $langs) {
            $IntegrationResults[$keyword] = $this->Connect($ServicePerformance, $keyword,$langs);
        }
        $this->ProcessIntegrationResults($DTO,$IntegrationResults,$mainLanguage,$langArr);
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
