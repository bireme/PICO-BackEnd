<?php

namespace PICOExplorer\Models\DataModels;

use PICOExplorer\Models\MainModelsModel;

class DeCSModel extends MainModelsModel
{

    public static final function requestRules()
    {
        return [
            'InitialData' => 'required|array|min:1',
            'InitialData.PICOnum' => 'required|integer|size:1',
            'InitialData.mainLanguage' => 'required|string|in:es.pt.en.fr',
            'InitialData.SavedData' => 'nullable|array',
            'InitialData.KeywordList' => 'required|array',
        ];
    }

    public static final function responseRules()
    {
        return [
            'results' => 'required|array|min:1',
            'results.SavedData' => 'required|string|min:1',
            'results.HTMLDescriptors' => 'required|string|min:1',
            'results.HTMLDeCS' => 'required|string|min:1',
        ];
    }

    public final function ControllerTestData()
    {
        return json_encode([
            'SavedData' => '',
            'KeywordList' => [
                0 => json_encode(['keyword'=>'dengue','langs'=>['en']]),
                1 => json_encode(['keyword'=>'zika','langs'=>['en']]),
            ],
            'PICOnum' => 1,
            'mainLanguage' => 'en',
        ]);
    }

    public static final function AttributeRules()
    {
        return [
            'decodedKeywordList' => ['required|array|min:0'],
            'PreviousData' => ['required|array|min:0'],
            'SavedData' => ['required|array|min:0'],
            'ProcessedDescriptors' => ['required|array|min:0'],
            'ProcessedDeCS' => ['required|array|min:0'],
            'DescriptorsHTML' => ['required|string|min:1'],
            'DeCSHTML' => ['required|string|min:1'],
        ];
    }
}
