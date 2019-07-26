<?php

namespace PICOExplorer\Services\ResultsNumber;

use PICOExplorer\Http\Controllers\PICO\IntegrationResultsNumberController;

abstract class ResultsNumberInternalConnector extends PICOIntegrationModel
{

    public final function ConnectToIntegration($UntitledData)
    {
        $IntegrationController = new IntegrationResultsNumberController();
        return $this->PICOIntegration($UntitledData, $IntegrationController);
    }

}
