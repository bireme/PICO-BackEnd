<?php

namespace PICOExplorer\Facades;

/**
 * Class ResultsNumberBIREMEFacade
 * @package PICOExplorer\Services\BIREMEImporter
 */
class ServicePerformanceFacade extends PICOServiceFacade
{
    protected static function getFacadeAccessor()
    {
        return 'serviceperformance'; // the IoC binding.
    }

}
