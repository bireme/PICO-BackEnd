<?php

namespace PICOExplorer\Models\DataModels;

use PICOExplorer\Models\MainModelsModel;

class BasicModel extends MainModelsModel
{

    public static final function requestRules()
    {
        return [
            'InitialData' => 'required|array|min:1',
        ];
    }

    public static final function responseRules()
    {
        return [
            'results' => 'required|array|min:1',
        ];
    }

    public final function ControllerTestData()
    {
        return [];
    }

    public static final function AttributeRules()
    {
        return [

        ];
    }

}
