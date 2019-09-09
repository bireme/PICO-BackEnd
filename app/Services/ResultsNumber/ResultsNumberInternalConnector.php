<?php

namespace PICOExplorer\Services\ResultsNumber;

use PICOExplorer\Http\Controllers\PICO\IntegrationResultsNumberController;
use PICOExplorer\Services\ServiceModels\PICOIntegrationModel;

abstract class ResultsNumberInternalConnector extends PICOIntegrationModel
{

    public final function ConnectToIntegration($data)
    {
        $IntegrationController = new IntegrationResultsNumberController();
        return $this->PICOIntegration($data, $IntegrationController);
    }

}
