<?php

namespace PICOExplorer\Models\DataModels;

use PICOExplorer\Models\MainModelsModel;

class IntegrationResultsNumberModel extends MainModelsModel
{

    public static final function requestRules(){
        return [
            'InitialData' => 'required|array|min:1',
            'InitialData.*' => 'required|string|distinct|min:1',
            'InitialData.*.*' => 'required|string|distinct|min:1',
        ];
    }

    public static final function responseRules(){
        return [
            'results' => 'required|array|min:1',
            'results.*.*' => 'required|array|in:query.resultsNumber.resultsURL',
            'results.*.*.query' => 'required|string|min:1',
            'results.*.*.resultsNumber' => 'required|integer',
            'results.*.*.resultsURL' => 'required|string|min:1',
        ];
    }

    public final function ControllerTestData()
    {
        return json_encode([

        ]);
    }

    public static final function AttributeRules()
    {
        return [
            'ProcessedQueries',
        ];
    }

}
