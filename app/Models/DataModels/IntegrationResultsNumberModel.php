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
          'global' => 'dengue AND (((Dengue OR ("Break bone fever" OR "Break-bone fever" OR "Breakbone fever" OR "Classical dengue" OR "Classical dengue fever" OR "Classical dengue fevers" OR "Classical dengues" OR "Dengue fever" OR Dengue OR "Dengue hemorrhagic fever" OR "Dengue shock syndrome" OR "Severe dengue" OR "Severe dengues" OR "Philippine hemorrhagic fever" OR "Singapore hemorrhagic fever" OR "Thai hemorrhagic fever" OR "Hemorrhagic dengue" OR "Hemorrhagic dengues") OR ("Breakbone fever virus" OR "Breakbone fever viruses" OR "Dengue viruses" OR "Dengue virus") OR ("Dengue hemorrhagic fever" OR "Dengue shock syndrome" OR "Severe dengue" OR "Severe dengues" OR "Philippine hemorrhagic fever" OR "Singapore hemorrhagic fever" OR "Thai hemorrhagic fever" OR "Hemorrhagic dengue" OR "Hemorrhagic dengues") OR ("Dengue vaccines"))) OR (Zika OR ("Zika Virus" OR ZikV) OR ("Zika Virus Disease" OR "Zika Fever" OR "ZikV Infection" OR "Zika Virus Infection")))',
          'local' => '(((Dengue OR ("Break bone fever" OR "Break-bone fever" OR "Breakbone fever" OR "Classical dengue" OR "Classical dengue fever" OR "Classical dengue fevers" OR "Classical dengues" OR "Dengue fever" OR Dengue OR "Dengue hemorrhagic fever" OR "Dengue shock syndrome" OR "Severe dengue" OR "Severe dengues" OR "Philippine hemorrhagic fever" OR "Singapore hemorrhagic fever" OR "Thai hemorrhagic fever" OR "Hemorrhagic dengue" OR "Hemorrhagic dengues") OR ("Breakbone fever virus" OR "Breakbone fever viruses" OR "Dengue viruses" OR "Dengue virus") OR ("Dengue hemorrhagic fever" OR "Dengue shock syndrome" OR "Severe dengue" OR "Severe dengues" OR "Philippine hemorrhagic fever" OR "Singapore hemorrhagic fever" OR "Thai hemorrhagic fever" OR "Hemorrhagic dengue" OR "Hemorrhagic dengues") OR ("Dengue vaccines"))) OR (Zika OR ("Zika Virus" OR ZikV) OR ("Zika Virus Disease" OR "Zika Fever" OR "ZikV Infection" OR "Zika Virus Infection")))'
        ]);
    }

    public static final function AttributeRules()
    {
        return [
            'ProcessedQueries',
        ];
    }

}
