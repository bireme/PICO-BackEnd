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
use Throwable;

abstract class PICOIntegrationModel Extends PICOServiceModel implements PICOServiceIntegration
{

    public final function PICOIntegration(array $data, MainControllerInterface $IntegrationController)
    {
        $timer = $this->ServicePerformance->newConnectionTimer('integrationtimer-' . get_class($this) . '-to-' . get_class($IntegrationController));
        $results = null;
        $wasSuccessful = false;
        $previousErr = null;
        try {
            $timer = $this->ServicePerformance->newConnectionTimer('inner-' . get_class($this) . '-' . get_class($IntegrationController));
            $results = $IntegrationController->outerBind($data);
            if (!($results)) {
                throw new ContactedComponentReturnedNull(['caller' => get_class($this), 'target' => get_class($IntegrationController)]);
            } elseif ($results instanceof JsonResponse) {
                throw new ContactedComponentReturningJSONResponseInsteadData(['caller' => get_class($this), 'target' => get_class($IntegrationController),'response'=>$results]);
            } elseif (array_key_exists('Error', $results)) {
                $previousErr = $results['Error'];
                throw new ErrorContactingAnotherComponent(['caller' => get_class($this), 'target' => get_class($IntegrationController), 'ErrorInTarget' => $previousErr]);
            } else {
                $wasSuccessful = true;
            }
        } catch (Throwable $ex) {
            ExceptionLoggerFacade::ReportException($ex);
            throw new ErrorInsideAnotherComponent(['caller' => get_class($this), 'target' => get_class($IntegrationController), 'ErrorInTarget' => $ex->getMessage()],$ex);
        } finally {
            $time = $timer->Stop();
            $info = 'Connection Failed';
            if ($wasSuccessful) {
                $info = 'Connection Success. result.size=' . strlen($results);
            } else {
                if ($previousErr) {
                    $info = $info . ': ' . $previousErr;
                }
            }
            $timer->Stop($info);
            if ($wasSuccessful) {
                if (!($results)) {
                    throw new PICOIntegrationReturnedNull(['referer' => __METHOD__ . '@' . get_class($this), 'Controller' => get_class($IntegrationController)]);
                } else {
                    return $results;
                }
            }
        }
    }

}
