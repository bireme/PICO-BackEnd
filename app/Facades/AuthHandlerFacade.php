<?php

namespace PICOExplorer\Facades;

/**
 * Class ResultsNumberBIREMEFacade
 * @package PICOExplorer\Services\BIREMEImporter
 */
class AuthHandlerFacade extends PICOServiceFacade
{
    protected static function getFacadeAccessor()
    {
        return 'authhandler'; // the IoC binding.
    }

}
