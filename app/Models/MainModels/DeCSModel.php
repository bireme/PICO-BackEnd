<?php

namespace PICOExplorer\Models\MainModels;

class DeCSModel extends MainModelsModel
{

    protected static final function FillableAttributes()
    {
        return [
            'KeywordList',
            'DeCSDescriptorsHTML',
            'SavedData',
            'DeCSHTML',
            'QuerySplit',
        ];
    }

    public function getAttributeKeywordList($value)
    {
        return $value;
    }

    public function getAttributeDeCSDescriptorsHTML($value)
    {
        return $value;
    }

    public function getAttributeDeCSHTML($value)
    {
        return $value;
    }

    public function getAttributeQuerySplit($value)
    {
        return $value;
    }
}
