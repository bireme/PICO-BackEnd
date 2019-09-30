<?php

namespace PICOExplorer\Services\ServiceModels;

use Illuminate\Http\JsonResponse;
use PICOExplorer\Exceptions\Exceptions\AppError\ContactedComponentReturnedNull;
use PICOExplorer\Exceptions\Exceptions\AppError\ContactedComponentReturningJSONResponseInsteadData;
use PICOExplorer\Exceptions\Exceptions\AppError\ErrorContactingAnotherComponent;
use PICOExplorer\Exceptions\Exceptions\AppError\ErrorInsideAnotherComponent;
use PICOExplorer\Exceptions\Exceptions\AppError\PICOIntegrationReturnedNull;
use PICOExplorer\Facades\ExceptionLoggerFacade;
use PICOExplorer\Http\Controllers\PICO\MainControllerInterface;
use PICOExplorer\Services\AdvancedLogger\Exceptions\DontCatchException;
use ServicePerformanceSV;
use Throwable;

trait ToControllerIntegrationTrait
{

    protected final function ToControllerIntegration(ServicePerformanceSV $ServicePerformance, MainControllerInterface $IntegrationController, array $data=null)
    {
        $results = 'Unset';
        $wasSuccessful = false;
        $previousErr = null;
        $timer=null;
        try {
            $timer = $ServicePerformance->newConnectionTimer('inner-' . get_class($this) . '-' . get_class($IntegrationController));
            $results = $IntegrationController->outerBind($data);
            $wasSuccessful = true;
        } catch (DontCatchException $ex) {
            //
        } finally {
            $timer->Stop();
            if ($wasSuccessful) {
                if (!($results) || $results==='Unset') {
                    throw new PICOIntegrationReturnedNull(['referer' => __METHOD__ . '@' . get_class($this), 'Controller' => get_class($IntegrationController),'previousErr'=>$previousErr]);
                }
            }
            return $results;
        }
    }
}
