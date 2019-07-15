<?php

namespace PICOExplorer\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class ResultsNumberBIREME
 * @package PICOExplorer\Services\BIREMEImporter
 */
class QueryProcess extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'queryprocess'; // the IoC binding.
    }

}
