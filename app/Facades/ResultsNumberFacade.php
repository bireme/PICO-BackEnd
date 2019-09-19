<?php


namespace PICOExplorer\Facades;

class ResultsNumberFacade extends AdvancedFacade
{
    protected static function getFacadeAccessor()
    {
        return 'resultsnumber'; // the IoC binding.
    }
}
