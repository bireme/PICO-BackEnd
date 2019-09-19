<?php

namespace PICOExplorer\Services\ResultsNumber;

use PICOExplorer\Http\Controllers\PICO\IntegrationResultsNumberController;
use PICOExplorer\Models\DataTransferObject;
use PICOExplorer\Services\ServiceModels\ServiceEntryPointInterface;
use PICOExplorer\Services\ServiceModels\ToControllerIntegrationTrait;
use ServicePerformanceSV;

class ResultsNumberProcessBridge extends ResultsNumberSupport implements ServiceEntryPointInterface
{

    use ToControllerIntegrationTrait;

    protected final function Process(ServicePerformanceSV $ServicePerformance, DataTransferObject $DTO, $InitialData)
    {
        $ResultsClusters = $this->buildGlobalAndLocalArrays($InitialData);
        $ProcessedQueries = [];
        foreach ($ResultsClusters as $key => $ResultsCluster) {
            $ProcessedQueries[$key] = $this->BuildQuery($ResultsCluster);
        }
        return $this->Explore($ServicePerformance, $DTO,$ProcessedQueries);
    }

    ///////////////////////////////////////////////////////////////////
    //INNER FUNCTIONS
    ///////////////////////////////////////////////////////////////////

    private function Explore(ServicePerformanceSV $ServicePerformance, DataTransferObject $DTO,$ProcessedQueries)
    {
        $DTO->SaveToModel(get_class($this), ['ProcessedQueries' => $ProcessedQueries]);
        $results = $this->Connect($ServicePerformance, $ProcessedQueries);
        return $results;
    }

    private final function Connect(ServicePerformanceSV $ServicePerformance, $data)
    {
        $IntegrationController = new IntegrationResultsNumberController();
        return $this->ToControllerIntegration($ServicePerformance,$IntegrationController,$data);
    }

}
