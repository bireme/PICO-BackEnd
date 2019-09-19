<?php

namespace PICOExplorer\Services\DeCS\Looper;

use PICOExplorer\Http\Controllers\PICO\IntegrationDeCSController;
use PICOExplorer\Services\ServiceModels\ParallelServiceEntryPoint;
use PICOExplorer\Services\ServiceModels\ParallelServiceEntryPointInterface;
use PICOExplorer\Services\ServiceModels\ToControllerIntegrationTrait;
use ServicePerformanceSV;

class DeCSLooperBridge extends ParallelServiceEntryPoint implements ParallelServiceEntryPointInterface
{

    use ToControllerIntegrationTrait;

    protected final function Process(ServicePerformanceSV $ServicePerformance, $InitialData = null)
    {
        $IntegrationResults = $this->Connect($ServicePerformance, $InitialData);
        return $IntegrationResults;
    }

///////////////////////////////////////////////////////////////////
//INNER FUNCTIONS
///////////////////////////////////////////////////////////////////

    private final function Connect(ServicePerformanceSV $ServicePerformance, $data)
    {
        $IntegrationController = new IntegrationDeCSController();
        return $this->ToControllerIntegration($ServicePerformance, $IntegrationController, $data);
    }

}
