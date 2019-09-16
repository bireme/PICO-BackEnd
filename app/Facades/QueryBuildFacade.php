<?php

namespace PICOExplorer\Facades;

/**
 * Class ResultsNumberBIREMEFacade
 * @package PICOExplorer\Services\BIREMEImporter
 */
class QueryBuildFacade extends PICOServiceFacade
{
    protected static function getFacadeAccessor()
    {
        return 'querybuild'; // the IoC binding.
    }

}
