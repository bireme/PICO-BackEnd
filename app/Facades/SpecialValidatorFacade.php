<?php


namespace PICOExplorer\Facades;

use Illuminate\Support\Facades\Facade;

class SpecialValidatorFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'specialvalidator'; // the IoC binding.
    }
}
