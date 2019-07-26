<?php

namespace PICOExplorer\Services\ServiceModels;

use PICOExplorer\Http\Controllers\PICO\MainControllerInterface;

interface PICOServiceIntegration
{

    public function ConnectToIntegration($UntitledData);

    public function PICOIntegration(array $UntitledData, MainControllerInterface $IntegrationController);

}
