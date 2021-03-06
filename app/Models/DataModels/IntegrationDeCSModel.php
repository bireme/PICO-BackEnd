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
            'keyword' => 'heart',
            'langs' => ['en'],
        ]);
    }


    public static final function AttributeRules()
    {
        return [
            'keyword' => ['string|min:1'],
            'langs' => ['array|min:1'],
            'MainTreeList'=>['array|min:0'],
            'WishList'=>['array|min:0'],
            'ResultsByTreeId'=>['array|min:0'],
            'FinalResults' =>['array|min:0'],
            'LevelList' =>['required|array|min:0'],
            'TotalCount'=>['integer|min:0'],
            'Queue'=>['array|min:0'],
            'TotalList' =>['array|min:0'],
        ];
    }

}
