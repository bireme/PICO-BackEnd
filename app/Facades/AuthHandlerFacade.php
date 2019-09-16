<?php

namespace PICOExplorer\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class ResultsNumberBIREMEFacade
 * @package PICOExplorer\Services\BIREMEImporter
 */
class AuthHandlerFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'authhandler'; // the IoC binding.
    }

}
