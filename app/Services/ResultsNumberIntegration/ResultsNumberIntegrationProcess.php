<?php

namespace PICOExplorer\Services\ResultsNumberIntegration;

use PICOExplorer\Facades\ResultsNumberIntegrationLooperFacade;
use PICOExplorer\Models\DataTransferObject;
use PICOExplorer\Services\ServiceModels\ServiceEntryPoint;
use PICOExplorer\Services\ServiceModels\ServiceEntryPointInterface;
use PICOExplorer\Services\ServiceModels\ToParallelServiceIntegrationTrait;
use ServicePerformanceSV;

class ResultsNumberIntegrationProcess extends ServiceEntryPoint implements ServiceEntryPointInterface
{

    use ToParallelServiceIntegrationTrait;

    protected final function Process(ServicePerformanceSV $ServicePerformance, DataTransferObject $DTO, $InitialData)
    {
        $results = [];
        $data = [
            'output' => 'xml',
            'count' => 20,
        ];
        foreach ($InitialData as $key => $query) {
            $results[$key] = $this->Explore($ServicePerformance, $query, $data);
        }
        return $results;
    }

    private final function Explore(ServicePerformanceSV $ServicePerformance, string $query, array $data)
    {
        $data['q'] = $query;
        $XMLresults=$this->Connect($ServicePerformance, $data);
        $results = [];
        $results['ResultsNumber'] = $XMLresults;
        $results['query'] = $query;
        $results['ResultsURL'] = 'http://pesquisa.bvsalud.org/portal/?count=20&q=' . $results['query'];
        return $results;
    }

    ///////////////////////////////////////////////////////////////////
    //INNER FUNCTIONS
    ///////////////////////////////////////////////////////////////////

    private final function Connect(ServicePerformanceSV $ServicePerformance, $data)
    {
        $AdvancedFacade = new ResultsNumberIntegrationLooperFacade();
        $XMLresults = $this->ToParallelServiceIntegration($ServicePerformance, $AdvancedFacade, $data);
        return $XMLresults;
    }

}
