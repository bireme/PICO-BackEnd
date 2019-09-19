<?php


namespace PICOExplorer\Facades;

class DeCSIntegrationLooperFacade extends AdvancedFacade
{
    protected static function getFacadeAccessor()
    {
        return 'decsintegrationlooper'; // the IoC binding.
    }
}
