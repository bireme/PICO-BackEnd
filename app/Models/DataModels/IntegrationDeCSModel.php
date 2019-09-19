<?php

namespace PICOExplorer\Models\DataModels;

use PICOExplorer\Models\MainModelsModel;

class IntegrationDeCSModel extends MainModelsModel
{

    public static final function requestRules()
    {
        return [
            'InitialData' => 'required|array|min:1',
            'InitialData.keyword' => 'required|string|min:1',
            'InitialData.langs' => 'required|array|min:1',
            'InitialData.langs.*' => 'required|string|in:es.pt.en.fr',
        ];
    }

    public static final function responseRules()
    {
        return [
            'results' => 'required|array|min:1',
            'results.ResultsByTreeId' => 'array|min:0',
        ];
    }

    public final function ControllerTestData()
    {
        return json_encode([
            'keyword' => 'febre amarela',
            'langs' => ['es'],
        ]);
    }


    public static final function AttributeRules()
    {
        return [
            'keyword' => ['string|min:1'],
            'langs' => ['array|min:1'],
            'log' => ['string|min:0'],
            'logcount' => ['array|min:0'],
            'PendingMainTrees'=>['array|min:0'],
            'PendingDescendants'=>['array|min:0'],
            'MainTreeList'=>['array|min:0'],
            'ExploredTrees'=>['array|min:0'],
            'TmpResults'=>['array|min:0'],
        ];
    }

}
