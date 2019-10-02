<?php

namespace PICOExplorer\Services\ServiceModels;

use PICOExplorer\Exceptions\Exceptions\AppError\ErrorInsideAnotherComponent;
use PICOExplorer\Exceptions\Exceptions\AppError\PICOIntegrationReturnedNull;
use PICOExplorer\Facades\AdvancedFacade;
use PICOExplorer\Facades\ExceptionLoggerFacade;
use ServicePerformanceSV;
use Throwable;

trait ToParallelServiceIntegrationTrait
{

    protected final function ToParallelServiceIntegration(ServicePerformanceSV $ServicePerformance, AdvancedFacade $ParallelFacade, array $InitialData = null)
    {
        $results = null;
        $wasSuccessful = false;
        $previousErr = null;
        $timer = null;
        try {
            $timer = $ServicePerformance->newConnectionTimer('inner-' . get_class($this) . '-' . get_class($ParallelFacade));
            $results = $ParallelFacade::getParallel($ServicePerformance, $InitialData);
            $wasSuccessful = true;
        } catch (Throwable $ex) {
            ExceptionLoggerFacade::ReportException($ex);
            throw $ex;
        } finally {
            $timer->Stop();
        }
        return $results;
    }

}
