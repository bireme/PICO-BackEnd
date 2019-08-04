<?php

namespace PICOExplorer\Http\Controllers\PICO;

use PICOExplorer\Models\MainModels\IntegrationDeCSModel;
use PICOExplorer\Facades\DeCSBIREMEFacade;

class IntegrationDeCSController extends BaseMainController implements MainControllerInterface
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
            'Data' => 'required|array|min:1',
            'Data.results' => 'required|string|min:1',
            'Data.HTMLDescriptors' => 'required|string|min:1',
            'Data.HTMLDeCS' => 'required|string|min:1',
            'Data.QuerySplit' => 'required|string|min:1',
        ];
    }

    public function create()
    {
        return [
            'model' => new IntegrationDeCSModel(),
            'service' => new DeCSBIREMEFacade(),
        ];
    }


    public function TestData()
    {
        return json_encode([
            'keyword' => 'break bone fever',
            'langs' => ['es'],
        ]);
    }

}
