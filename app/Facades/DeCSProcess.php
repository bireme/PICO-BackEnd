<?php

namespace PICOExplorer\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class ResultsNumberBIREME
 * @package PICOExplorer\Services\BIREMEImporter
 */
class DeCSProcess extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'decsprocess'; // the IoC binding.
    }

}
