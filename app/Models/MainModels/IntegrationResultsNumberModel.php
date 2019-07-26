<?php

namespace PICOExplorer\Models\MainModels;

class IntegrationResultsNumberModel extends MainModelsModel
{

    protected static final function FillableAttributes()
    {
        return [
            'ProcessedQueries',
        ];
    }

}
