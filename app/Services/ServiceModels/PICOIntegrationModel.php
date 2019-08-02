<?php

namespace PICOExplorer\Services\ServiceModels;

use PICOExplorer\Exceptions\Exceptions\AppError\PICOIntegrationReturnedNull;
use PICOExplorer\Http\Controllers\PICO\MainControllerInterface;
use PICOExplorer\Services\AdvancedLogger\Traits\SpecialValidator;
use PICOExplorer\Services\ServiceModels\PICOServiceIntegration;
use PICOExplorer\Services\ServiceModels\PICOServiceModel;

abstract class PICOIntegrationModel Extends PICOServiceModel implements PICOServiceIntegration
{

    use SpecialValidator;

    public final function PICOIntegration(array $data, MainControllerInterface $IntegrationController)
    {
        $ruleArr = $IntegrationController::responseRules();
        $results = $IntegrationController->core(json_encode($data));
        if (!($results)) {
            throw new PICOIntegrationReturnedNull(['referer' => __METHOD__ . '@' . get_class($this), 'Controller' => get_class($IntegrationController)]);
        }
        $this->SpecialValidate($results, $ruleArr, __METHOD__ . '@' . get_class($this), get_class($this));
        return $results;
    }

}
