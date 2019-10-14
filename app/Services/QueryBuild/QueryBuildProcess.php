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
        $this->decodeQuerySplit($DTO, $InitialData['QuerySplit']);
        $this->getImproveEquation($DTO,$InitialData['ImproveSearchQuery']);
        $this->BuildnewEquation($DTO, $InitialData['SelectedDescriptors']);
        $results = [
            'newQuery' => $DTO->getAttr('newQuery'),
            'OldSelectedDescriptors' => json_encode($InitialData['SelectedDescriptors']),
            'ImproveSearchWords' =>json_encode($DTO->getAttr('ImproveSearchWords')),
            'ImproveSearchQuery' =>$DTO->getAttr('ImproveSearchQuery'),
        ];
        return $results;
    }

}
