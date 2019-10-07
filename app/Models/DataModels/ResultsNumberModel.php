<?php

namespace PICOExplorer\Models\DataModels;

use PICOExplorer\Models\MainModelsModel;

class ResultsNumberModel extends MainModelsModel
{

    public static final function requestRules()
    {
        return [
            'InitialData' => 'required|array|min:1',
            'InitialData.PICOnum' => 'required|integer|size:1',
            'InitialData.mainLanguage' => 'required|string|in:es.pt.en.fr',
            'InitialData.queryobject' => 'required|array|size:5',
            'InitialData.queryobject.*' => 'required|array|distinct|in:PICO1.PICO2.PICO3.PICO4.PICO5',
            'InitialData.queryobject.*.*' => 'required|array|size:2|distinct|in:field.query',
            'InitialData.queryobject.*.*.query' => 'required|string|min:0',
            'InitialData.queryobject.*.*.field' => 'required|integer',
        ];
    }

    public static final function responseRules()
    {
        return [
            'results' => 'required|array|min:1',
            'results.results' => 'required|array|min:1',
            'results.NewEquation' => 'required|string|min:0',
            'results.GlobalTitle' => 'required|string|min:0',
        ];
    }

    public final function ControllerTestData()
    {
        return json_encode([
                "PICOnum" => "1",
                "queryobject" => [
                    "PICO1" => [
                        "query" => "dengue",
                        "field" => 0,
                    ],
                    "PICO2" => [
                        "query" => "",
                        "field" => 0,
                    ],
                    "PICO3" => [
                        "query" => "",
                        "field" => 0,
                    ],
                    "PICO4" => [
                        "query" => "",
                        "field" => 0,
                    ],
                    "PICO5" => [
                        "query" => "",
                        "field" => -1,
                    ],
                ]
            ]
        );
    }

    public static final function AttributeRules()
    {
        return [
            'ProcessedQueries' => [
                'ProcessedQueries' => 'required|array|min:1',
                'ProcessedQueries.*' => 'required|string|distinct|min:1',
                'ProcessedQueries.*.*' => 'required|string|distinct|min:1',
            ],
            'NewEquation' => ['required|string|min:0',],
            'GlobalTitle' => ['required|string|min:0',],
        ];
    }

}
