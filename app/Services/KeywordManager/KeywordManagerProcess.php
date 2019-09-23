<?php

namespace PICOExplorer\Services\KeywordManager;

use PICOExplorer\Models\DataTransferObject;
use PICOExplorer\Services\ServiceModels\ServiceEntryPointInterface;
use PICOExplorer\Services\ServiceModels\ToParallelServiceIntegrationTrait;
use ServicePerformanceSV;

class KeywordManagerProcess extends KeywordManagerSupport implements ServiceEntryPointInterface
{

    use ToParallelServiceIntegrationTrait;

    protected final function Process(ServicePerformanceSV $ServicePerformance, DataTransferObject $DTO, $InitialData)
    {
        $InitialData= $DTO->getInitialData();
        $this->DecodePreviousData($DTO,$InitialData['SavedData']??'');
        $QueryProcessed = $this->ProcessInitialQuery($InitialData['query']);
        $this->BuildKeywordList($DTO,$QueryProcessed,$InitialData['langs']);
        $this->BuildFormData($DTO,$InitialData['PICOnum']);
        $results = [
            'HTML' => $DTO->getAttr('HTML'),
        ];
        return $results;
    }

}
