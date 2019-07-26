<?php

namespace PICOExplorer\Models\MainModels;

class IntegrationDeCSModel extends MainModelsModel
{

    protected static final function FillableAttributes()
    {
        return [
            'ProcessedQueries',
        ];
    }

}
