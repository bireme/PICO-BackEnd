<?php


namespace PICOExplorer\Facades;

class QueryBuildFacade extends AdvancedFacade
{
    protected static function getFacadeAccessor()
    {
        return 'querybuild'; // the IoC binding.
    }
}
