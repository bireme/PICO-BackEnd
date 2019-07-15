<?php

namespace PICOExplorer\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class ResultsNumberBIREME
 * @package PICOExplorer\Services\BIREMEImporter
 */
class Timer extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'timer'; // the IoC binding.
    }

}
