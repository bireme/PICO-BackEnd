<?php


namespace PICOExplorer\Facades;

class DeCSLooperFacade extends AdvancedFacade
{
    protected static function getFacadeAccessor()
    {
        return 'decslooper'; // the IoC binding.
    }
}
