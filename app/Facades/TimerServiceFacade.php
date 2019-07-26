<?php

namespace PICOExplorer\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class ResultsNumberBIREMEFacade
 * @package PICOExplorer\Services\BIREMEImporter
 */
class TimerServiceFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'timerservice'; // the IoC binding.
    }

}
