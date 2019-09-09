<?php

namespace PICOExplorer\Models\DataModels;

use PICOExplorer\Models\MainModelsModel;

class IntegrationDeCSModel extends MainModelsModel
{

    public static final function requestRules()
    {
        return [
            'InitialData' => 'required|array|min:1',
            'InitialData.query' => 'required|string|min:1',
            'InitialData.mainLanguage' => 'required|string|min:1',
            'InitialData.PICOnum' => 'required|string|min:1',
            'InitialData.langs' => 'required|array|min:1',
            'InitialData.langs.*' => 'required|string|in:es.pt.en.fr',
        ];
    }

    public static final function responseRules()
    {
        return [
            'results' => 'required|array|min:1',
            'results.results' => 'required|string|min:1',
            'results.HTMLDescriptors' => 'required|string|min:1',
            'results.HTMLDeCS' => 'required|string|min:1',
            'results.QuerySplit' => 'required|string|min:1',
        ];
    }

    public final function ControllerTestData()
    {
        return json_encode([
            'keyword' => 'break bone fever',
            'langs' => ['es'],
        ]);
    }


    public static final function AttributeRules()
    {
        return [
            'PendingMainTrees'=>[],
            'PendingDescendants'=>[],
            'MainTreeList'=>[],
            'ExploredTrees'=>[],
            'AllTrees'=>[]
        ];
    }

}
