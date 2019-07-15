<?php

namespace PICOExplorer\Http\Controllers\MainControllers;

use PICOExplorer\Models\MainModels\IntegrationResultsNumberModel;
use PICOExplorer\Models\MainModels\MainModelsModel;
use PICOExplorer\Services\TimerService\Timer;
use ResultsNumberProcess;

class ResultsNumberController extends BaseMainController implements MainControllerInterface
{

    public function create()
    {
        return new IntegrationResultsNumberModel();
    }

    public function MainOperation(MainModelsModel $model, Timer $globalTimer)
    {
        ResultsNumberProcess::get($model, $globalTimer);
    }

    public function TestData()
    {
        return [
            ['InitialData' =>['PICOnum' => '2','queryobject' => ['PICO1' => ['query' => 'dengue','field' => 0],'PICO2' => ['query' => '(((Dengue OR ("Break bone fever" OR "Break-bone fever" OR "Breakbone fever" OR "Classical dengue" OR "Classical dengue fever" OR "Classical dengue fevers" OR "Classical dengues" OR "Dengue fever" OR Dengue OR "Dengue hemorrhagic fever" OR "Dengue shock syndrome" OR "Severe dengue" OR "Severe dengues" OR "Philippine hemorrhagic fever" OR "Singapore hemorrhagic fever" OR "Thai hemorrhagic fever" OR "Hemorrhagic dengue" OR "Hemorrhagic dengues") OR ("Breakbone fever virus" OR "Breakbone fever viruses" OR "Dengue viruses" OR "Dengue virus") OR ("Dengue hemorrhagic fever" OR "Dengue shock syndrome" OR "Severe dengue" OR "Severe dengues" OR "Philippine hemorrhagic fever" OR "Singapore hemorrhagic fever" OR "Thai hemorrhagic fever" OR "Hemorrhagic dengue" OR "Hemorrhagic dengues") OR ("Dengue vaccines"))) OR (Zika OR ("Zika Virus" OR ZikV) OR ("Zika Virus Disease" OR "Zika Fever" OR "ZikV Infection" OR "Zika Virus Infection")))','field' => 0],'PICO3' => ['query' => '','field' => 0],'PICO4' => ['query' => '','field' => 0],'PICO5' => ['query' => '','field' => -1]],'mainLanguage' => 'en']]
        ];
    }

}
