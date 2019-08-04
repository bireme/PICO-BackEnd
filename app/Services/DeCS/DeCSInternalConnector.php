<?php

namespace PICOExplorer\Services\DeCS;

use PICOExplorer\Http\Controllers\PICO\IntegrationDeCSController;
use PICOExplorer\Services\ServiceModels\PICOIntegrationModel;

abstract class DeCSInternalConnector extends PICOIntegrationModel
{

    protected $attributes=[];

    public final function ConnectToIntegration($UntitledData)
    {
        $IntegrationController = new IntegrationDeCSController();
        return $this->PICOIntegration($UntitledData, $IntegrationController);
    }

}
