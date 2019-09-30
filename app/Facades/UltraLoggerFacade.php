<?php


namespace PICOExplorer\Facades;

use Illuminate\Support\Facades\Facade;

class UltraLoggerFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'ultralogger'; // the IoC binding.
    }
}
