<?php


namespace PICOExplorer\Facades;

use Illuminate\Support\Facades\Facade;

class WarningsHandlerFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'warningshandler'; // the IoC binding.
    }
}
