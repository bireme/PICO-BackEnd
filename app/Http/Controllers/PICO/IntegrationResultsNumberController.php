<?php

namespace PICOExplorer\Http\Controllers\PICO;

use PICOExplorer\Models\MainModels\IntegrationResultsNumberModel;
use PICOExplorer\Facades\ResultsNumberBIREMEFacade;

class IntegrationResultsNumberController extends BaseMainController implements MainControllerInterface
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
            'Data' => 'required|array|min:1',
            'Data.*.*' => 'required|array|in:query.resultsNumber.resultsURL',
            'Data.*.*.query' => 'required|string|min:1',
            'Data.*.*.resultsNumber' => 'required|integer',
            'Data.*.*.resultsURL' => 'required|string|min:1',
        ];
    }

    public function create()
    {
        return [
            'model' => new IntegrationResultsNumberModel(),
            'service' => new ResultsNumberBIREMEFacade(),
        ];
    }

    public function TestData()
    {
        return json_encode([

        ]);
    }

}
