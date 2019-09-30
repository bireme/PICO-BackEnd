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
            throw new ErrorInsideAnotherComponent(['caller' => get_class($this), 'target' => get_class($ParallelFacade), 'ErrorInTarget' => $ex->getMessage()], $ex);
        } finally {
            $timer->Stop();
            if ($wasSuccessful) {
                if (!($results) || $results==='Unset') {
                    throw new PICOIntegrationReturnedNull(['referer' => __METHOD__ . '@' . get_class($this), 'Controller' => get_class($ParallelFacade), 'previousErr' => $previousErr]);
                }
            }
        }
        return $results;
    }

}
