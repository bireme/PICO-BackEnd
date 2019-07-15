<?php

namespace PICOExplorer\Models\MainModels;

class DeCSModel extends MainModelsModel
{

    protected $casts = [ //atr casted to native
        'PreviousData' => 'array',
        'query' => 'string',
        'langs' => 'array',
        'PICOnum' => 'int',
        'mainLanguage' => 'string',
    ];

    protected $fillable = [
        'PreviousData',
        'query',
        'langs',
        'PICOnum',
        'mainLanguage',
    ];

    protected $resultsArr = [
    ];

    protected $visible = [
    ];

}
