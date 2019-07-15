<?php

namespace PICOExplorer\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class ResultsNumberBIREME
 * @package PICOExplorer\Services\BIREMEImporter
 */
class ResultsNumberProcess extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'resultsnumberprocess'; // the IoC binding.
    }

}
