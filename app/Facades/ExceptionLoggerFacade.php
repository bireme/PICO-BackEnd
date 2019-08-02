<?php

namespace PICOExplorer\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class ResultsNumberBIREMEFacade
 * @package PICOExplorer\Services\BIREMEImporter
 */
class ExceptionLoggerFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'exceptionlogger'; // the IoC binding.
    }

}
