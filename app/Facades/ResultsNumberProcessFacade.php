<?php

namespace PICOExplorer\Facades;

/**
 * Class ResultsNumberBIREMEFacade
 * @package PICOExplorer\Services\BIREMEImporter
 */
class ResultsNumberProcessFacade extends PICOServiceFacade
{
    protected static function getFacadeAccessor()
    {
        return 'resultsnumberprocess'; // the IoC binding.
    }

}
