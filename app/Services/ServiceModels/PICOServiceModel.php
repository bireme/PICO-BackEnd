<?php

namespace PICOExplorer\Services\ServiceModels;

use PICOExplorer\Exceptions\Exceptions\AppError\ServiceResultsIsNull;
use PICOExplorer\Facades\ServicePerformanceFacade;
use PICOExplorer\Models\DataTransferObject;
use PICOExplorer\Services\AdvancedLogger\Exceptions\DontCatchException;

abstract class PICOServiceModel
{

    /**
     * @var DataTransferObject
     */
    protected $DTO;

    protected $ServicePerformance;

    abstract protected function Process();

    final public function get(DataTransferObject $DTO)
    {
        $this->DTO = $DTO;
        $tmp = new \ServicePerformanceSV();
        $this->ServicePerformance = $tmp->ServicePerformanceStart($this->DTO, get_class($this));
        $wasSuccess=false;
        try {
            $results = $this->Process();
            $this->setResults(get_class($this),$results);
            $wasSuccess=true;
        } catch (DontCatchException $ex) {
        } finally {
            $this->ServicePerformance->ServicePerformanceSummary($wasSuccess);
        }
    }

    protected function setResults(string $referer, array $data)
    {
        if (!($data)) {
            throw new ServiceResultsIsNull(['referer'=>$referer]);
        }
        $this->DTO->setResults(get_class($this),$data,'main');
    }

}
