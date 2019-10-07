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
        $queries = $this->BuildQueries($DTO,$InitialData);
        $IntegrationResults =  $this->Connect($ServicePerformance, $queries);
        $results =  [
            'Results' => $IntegrationResults,
            'NewEquation' => $DTO->getAttr('NewEquation'),
            'GlobalTitle' => $DTO->getAttr('GlobalTitle'),
        ];
        return $results;
    }

    ///////////////////////////////////////////////////////////////////
    //INNER FUNCTIONS
    ///////////////////////////////////////////////////////////////////

    private final function Connect(ServicePerformanceSV $ServicePerformance, $data)
    {
        $IntegrationController = new IntegrationResultsNumberController();
        return $this->ToControllerIntegration($ServicePerformance,$IntegrationController,$data);
    }

}
