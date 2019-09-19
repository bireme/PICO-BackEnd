<?php

namespace PICOExplorer\Services\ServiceModels;

use ServicePerformanceSV;

interface ParallelServiceEntryPointInterface
{

    public function getParallel(ServicePerformanceSV $ServicePerformance,$InitialData=null);

}
