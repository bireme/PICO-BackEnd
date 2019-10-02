<?php

namespace PICOExplorer\Services\ServiceModels;

use PICOExplorer\Exceptions\Exceptions\AppError\ServiceResultsIsNull;
use PICOExplorer\Models\DataTransferObject;
use PICOExplorer\Services\AdvancedLogger\Exceptions\DontCatchException;
use ServicePerformanceSV;

abstract class ServiceEntryPoint
{

    /**
     * @param DataTransferObject $DTO
     * @param array $InitialData
     * @param ServicePerformanceSV $ServicePerformance
     * @return mixed
     */
    abstract protected function Process(ServicePerformanceSV $ServicePerformance, DataTransferObject $DTO, $InitialData);

    final public function get(DataTransferObject $DTO)
    {
        $tmp = new ServicePerformanceSV();
        $ServicePerformance = $tmp->ServicePerformanceStart($DTO, get_class($this));
        $wasSuccess=false;
        try {
            $InitialData = $this->getInitialData($DTO);
            $results = $this->Process($ServicePerformance, $DTO,$InitialData);
            $this->setResults($DTO,get_class($this),$results);
            $wasSuccess=true;
        } catch (\Throwable $ex) {
            throw $ex;
        } finally {
            $ServicePerformance->ServicePerformanceSummary($wasSuccess);
        }
    }

    final protected function getInitialData(DataTransferObject $DTO){
        return $DTO->getInitialData();
    }

    final protected function setResults(DataTransferObject $DTO, string $referer, $data)
    {
        if (!($data)) {
            throw new ServiceResultsIsNull(['referer'=>$referer]);
        }
        $DTO->setResults(get_class($this),$data,'main');
    }

}
