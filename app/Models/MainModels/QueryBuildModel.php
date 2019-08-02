<?php

namespace PICOExplorer\Models\MainModels;

class QueryBuildModel extends MainModelsModel
{
    protected static final function FillableAttributes()
    {
        return [
            'ProcessedQueries',
            'QuerySplit',
            'results',
            'SelectedDescriptors',
            'ImproveSearchQuery',
            'newQuery',
        ];
    }

    public function getAttributequerySplit($value)
    {
        return $value;
    }

    public function getAttributeSelectedDescriptors($value)
    {
        return $value;
    }

    public function getAttributeImproveSearchQuery($value)
    {
        return $value;
    }

}
