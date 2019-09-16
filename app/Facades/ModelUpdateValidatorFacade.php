<?php

namespace PICOExplorer\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class ResultsNumberBIREMEFacade
 * @package PICOExplorer\Services\BIREMEImporter
 */
class ModelUpdateValidatorFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'modelupdatevalidator'; // the IoC binding.
    }

}
