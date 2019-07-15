<?php

namespace PICOExplorer\Models\MainModels;

class ResultsNumberModel extends MainModelsModel
{

    protected $casts = [ //atr casted to native
        'InitialData' => 'required|array',
        'InitialData.PICOnum' => 'required|integer|min:1|max:6',
        'InitialData.mainLanguage' => 'required|string|size:2',
        'InitialData.queryobject' => 'required|array',
        'InitialData.queryobject.*.query' => 'required|string|min:0',
        'InitialData.queryobject.*.field' => 'required|integer|min:0|max:4',
        'ProcessedQueries' => 'required|array|min:1',
        'ProcessedQueries.*' => 'required|string|distinct|min:1',
        'ProcessedQueries.*.*' => 'required|string|distinct|min:1',
    ];

    protected $fillable = [
        'ProcessedQueries',
    ];


    protected $resultsArr = [
    ];

    protected $visible = [
    ];

    public function getAttributeProcessedQueries($value){
        return $value;
    }
}
