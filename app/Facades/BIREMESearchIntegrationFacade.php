<?php

namespace PICOExplorer\Facades;

class BIREMESearchIntegrationFacade extends AdvancedFacade
{
    protected static function getFacadeAccessor()
    {
        return 'biremesearchintegration'; // the IoC binding.
    }
}
