<?php

namespace PICOExplorer\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class ResultsNumberBIREME
 * @package PICOExplorer\Services\BIREMEImporter
 */
class DeCSBIREME extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'decsbireme'; // the IoC binding.
    }

}
