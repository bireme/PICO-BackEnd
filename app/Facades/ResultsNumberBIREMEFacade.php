<?php

namespace PICOExplorer\Facades;

/**
 * Class ResultsNumberBIREMEFacade
 * @package PICOExplorer\Services\BIREMEImporter
 */
class ResultsNumberBIREMEFacade extends PICOServiceFacade
{
    protected static function getFacadeAccessor()
    {
        return 'resultsnumberbireme'; // the IoC binding.
    }

}
