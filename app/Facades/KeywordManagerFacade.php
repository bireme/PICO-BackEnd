<?php


namespace PICOExplorer\Facades;

class KeywordManagerFacade extends AdvancedFacade
{
    protected static function getFacadeAccessor()
    {
        return 'keywordmanager'; // the IoC binding.
    }
}
