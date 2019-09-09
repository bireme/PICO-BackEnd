<?php

namespace PICOExplorer\Models\DataModels;

use PICOExplorer\Models\MainModelsModel;

class TreeModel extends MainModelsModel
{

    public static final function requestRules()
    {
        return [];
    }

    public static final function responseRules()
    {
        return [];
    }

    public final function ControllerTestData()
    {
        return '';
    }

    public static final function AttributeRules()
    {
        return [
            'descendants' => [
            ],
            'content' => [
            ],
            'tree_id' => [
            ],
            'ExploredLanguages' => [
            ],
        ];
    }

}
