<?php


namespace PICOExplorer\Facades;

use Illuminate\Support\Facades\Facade;

class AdvancedLoggerFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'advancedlogger'; // the IoC binding.
    }
}
