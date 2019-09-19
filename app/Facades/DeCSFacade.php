<?php


namespace PICOExplorer\Facades;

class DeCSFacade extends AdvancedFacade
{
    protected static function getFacadeAccessor()
    {
        return 'decs'; // the IoC binding.
    }
}
