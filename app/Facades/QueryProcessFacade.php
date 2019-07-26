<?php

namespace PICOExplorer\Facades;

/**
 * Class ResultsNumberBIREMEFacade
 * @package PICOExplorer\Services\BIREMEImporter
 */
class QueryProcessFacade extends PICOServiceFacade
{
    protected static function getFacadeAccessor()
    {
        return 'queryprocess'; // the IoC binding.
    }

}
