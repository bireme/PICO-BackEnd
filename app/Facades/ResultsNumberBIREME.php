<?php

namespace PICOExplorer\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class ResultsNumberBIREME
 * @package PICOExplorer\Services\BIREMEImporter
 */
class ResultsNumberBIREME extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'resultsnumberbireme'; // the IoC binding.
    }

}
