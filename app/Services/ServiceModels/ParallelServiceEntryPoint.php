<?php

namespace PICOExplorer\Services\ServiceModels;

use PICOExplorer\Services\AdvancedLogger\Exceptions\DontCatchException;
use ServicePerformanceSV;

abstract class ParallelServiceEntryPoint
{

    /**
     * @param array $InitialData
     * @return mixed
     */
    abstract protected function Process(ServicePerformanceSV $ServicePerformance, $InitialData=null);

    final public function getParallel(ServicePerformanceSV $ServicePerformance,$InitialData=null)
    {
        $wasSuccess=false;
        $results=null;
        try {
            $results = $this->Process($ServicePerformance, $InitialData);
            $wasSuccess=true;
        }  catch (DontCatchException $ex) {
            //
        } finally {
            return $results;
        }
    }

}
