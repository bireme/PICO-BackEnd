<?php

namespace PICOExplorer\Models\MainModels;

class ResultsNumberModel extends MainModelsModel
{

    protected static final function FillableAttributes()
    {
        return [
            'ProcessedQueries',
        ];
    }

    public function getAttributeProcessedQueries($value)
    {
        return $value;
    }
}
