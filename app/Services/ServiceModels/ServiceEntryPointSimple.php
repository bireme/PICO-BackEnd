<?php

namespace PICOExplorer\Services\ServiceModels;

use PICOExplorer\Exceptions\Exceptions\AppError\ServiceResultsIsNull;
use PICOExplorer\Models\DataTransferObject;
use PICOExplorer\Services\AdvancedLogger\Exceptions\DontCatchException;
use ServicePerformanceSV;

abstract class ServiceEntryPointSimple
{

    /**
     * @param DataTransferObject $DTO
     * @param array $InitialData
     * @param ServicePerformanceSV $ServicePerformance
     * @return mixed
     */
    abstract protected function Process(ServicePerformanceSV $ServicePerformance, $InitialData);

    final public function get()
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



}
