<?php


namespace PICOExplorer\Facades;

class DeCSIntegrationFacade extends AdvancedFacade
{
    protected static function getFacadeAccessor()
    {
        return 'decsintegration'; // the IoC binding.
    }
}
