<?php

namespace PICOExplorer\Services\QueryBuild;

use PICOExplorer\Models\DataTransferObject;
use PICOExplorer\Services\ServiceModels\ServiceEntryPointInterface;
use ServicePerformanceSV;

class QueryBuildProcess extends QueryBuildSupport implements ServiceEntryPointInterface
{

    protected final function Process(ServicePerformanceSV $ServicePerformance, DataTransferObject $DTO, $InitialData)
    {
        $InitialData = $DTO->getInitialData($DTO);
        $this->BuildBaseEquation($DTO,$InitialData['PICOnum'],$InitialData['QuerySplit'],$InitialData['DeCSResults'],$InitialData['SelectedDescriptors']);
        $this->ImproveBasicEquation($DTO,$InitialData['ImproveSearchQuery']); //Falta por construir
        $results = $DTO->getAttr('ImprovedResults');
        return $results;
    }

}
