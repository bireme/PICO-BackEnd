<?php

namespace PICOExplorer\Facades;

/**
 * Class ResultsNumberBIREMEFacade
 * @package PICOExplorer\Services\BIREMEImporter
 */
class DeCSProcessFacade extends PICOServiceFacade
{
    protected static function getFacadeAccessor()
    {
        return 'decsprocess'; // the IoC binding.
    }

}
