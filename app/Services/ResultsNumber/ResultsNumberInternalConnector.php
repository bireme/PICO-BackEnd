<?php

namespace PICOExplorer\Services\ResultsNumber;

use PICOExplorer\Http\Controllers\PICO\IntegrationResultsNumberController;
use PICOExplorer\Services\ServiceModels\PICOIntegrationModel;

abstract class ResultsNumberInternalConnector extends PICOIntegrationModel
{

    public final function ConnectToIntegration($UntitledData)
    {
        $IntegrationController = new IntegrationResultsNumberController();
        return $this->PICOIntegration($UntitledData, $IntegrationController);
    }

}
