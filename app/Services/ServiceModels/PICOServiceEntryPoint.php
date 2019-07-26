<?php

namespace PICOExplorer\Services\ServiceModels;

use PICOExplorer\Models\MainModels\MainModelsModel;

interface PICOServiceEntryPoint
{

    public function Process();

    public function get(MainModelsModel $model, array $responseRules, $ParentTimer = null);

}
