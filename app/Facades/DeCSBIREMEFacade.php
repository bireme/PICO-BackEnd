<?php

namespace PICOExplorer\Facades;

/**
 * Class ResultsNumberBIREMEFacade
 * @package PICOExplorer\Services\BIREMEImporter
 */
class DeCSBIREMEFacade extends PICOServiceFacade
{
    protected static function getFacadeAccessor()
    {
        return 'decsbireme'; // the IoC binding.
    }

}
