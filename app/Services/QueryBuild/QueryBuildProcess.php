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
        $this->decodeOldSelectedDescriptors($DTO, $InitialData['OldSelectedDescriptors']);
        $this->decodeQuerySplit($DTO, $InitialData['QuerySplit']);
        $QuerySplit = $DTO->getAttr('QuerySplit');
        $this->BuildBaseEquation($DTO, $InitialData['SelectedDescriptors'],$QuerySplit);
        $this->ImproveBasicEquation($DTO,$InitialData['ImproveSearchQuery']);
        $results = [
            'newQuery' => $DTO->getAttr('newQuery'),
            'OldSelectedDescriptors' => $InitialData['SelectedDescriptors'],
        ];
        return $results;
    }

}
