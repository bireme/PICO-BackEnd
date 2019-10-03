<?php

namespace PICOExplorer\Services\QueryBuild;

use PICOExplorer\Models\DataTransferObject;
use PICOExplorer\Services\ServiceModels\ServiceEntryPointInterface;
use ServicePerformanceSV;

class QueryBuildProcess extends QueryBuildSupport implements ServiceEntryPointInterface
{

    protected final function Process(ServicePerformanceSV $ServicePerformance, DataTransferObject $DTO, $InitialData)
    {
        $InitialData = $DTO->getInitialData();
        $ImproveQuerySplit=$this->getImproveQuerySplit($InitialData['ImproveSearchQuery']);
        $this->decodeQuerySplit($DTO, $InitialData['QuerySplit']);
        $this->BuildBaseEquation($DTO, $InitialData['SelectedDescriptors']);
        $this->ImproveBasicEquation($DTO,$ImproveQuerySplit);
        $results = [
            'newQuery' => $DTO->getAttr('newQuery'),
            'OldSelectedDescriptors' => $InitialData['SelectedDescriptors'],
        ];
        return $results;
    }

}
