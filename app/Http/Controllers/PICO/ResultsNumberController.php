<?php

namespace PICOExplorer\Http\Controllers\PICO;

use PICOExplorer\Models\MainModels\ResultsNumberModel;
use PICOExplorer\Facades\ResultsNumberProcessFacade;

class ResultsNumberController extends BaseMainController implements MainControllerInterface
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
            'Data' => 'required|array|min:1',
            'Data.*' => 'required|array|in:query.resultsNumber.resultsURL',
            'Data.*.query' => 'required|string|min:1',
            'Data.*.resultsNumber' => 'required|integer',
            'Data.*.resultsURL' => 'required|string|min:1',
        ];
    }

    public function create()
    {
        return [
            'model' => new ResultsNumberModel(),
            'service' => new ResultsNumberProcessFacade(),
        ];
    }

    public function TestData()
    {
        return json_encode(
            ['PICOnum' => '2', 'queryobject' => ['PICO1' => ['query' => 'dengue', 'field' => 0], 'PICO2' => ['query' => '(((Dengue OR ("Break bone fever" OR "Break-bone fever" OR "Breakbone fever" OR "Classical dengue" OR "Classical dengue fever" OR "Classical dengue fevers" OR "Classical dengues" OR "Dengue fever" OR Dengue OR "Dengue hemorrhagic fever" OR "Dengue shock syndrome" OR "Severe dengue" OR "Severe dengues" OR "Philippine hemorrhagic fever" OR "Singapore hemorrhagic fever" OR "Thai hemorrhagic fever" OR "Hemorrhagic dengue" OR "Hemorrhagic dengues") OR ("Breakbone fever virus" OR "Breakbone fever viruses" OR "Dengue viruses" OR "Dengue virus") OR ("Dengue hemorrhagic fever" OR "Dengue shock syndrome" OR "Severe dengue" OR "Severe dengues" OR "Philippine hemorrhagic fever" OR "Singapore hemorrhagic fever" OR "Thai hemorrhagic fever" OR "Hemorrhagic dengue" OR "Hemorrhagic dengues") OR ("Dengue vaccines"))) OR (Zika OR ("Zika Virus" OR ZikV) OR ("Zika Virus Disease" OR "Zika Fever" OR "ZikV Infection" OR "Zika Virus Infection")))', 'field' => 0], 'PICO3' => ['query' => '', 'field' => 0], 'PICO4' => ['query' => '', 'field' => 0], 'PICO5' => ['query' => '', 'field' => -1]], 'mainLanguage' => 'en']
        );
    }

}
