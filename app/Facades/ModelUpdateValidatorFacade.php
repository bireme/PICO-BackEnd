<?php

namespace PICOExplorer\Facades;

/**
 * Class ResultsNumberBIREMEFacade
 * @package PICOExplorer\Services\BIREMEImporter
 */
class ModelUpdateValidatorFacade extends PICOServiceFacade
{
    protected static function getFacadeAccessor()
    {
        return 'modelupdatevalidator'; // the IoC binding.
    }

}
