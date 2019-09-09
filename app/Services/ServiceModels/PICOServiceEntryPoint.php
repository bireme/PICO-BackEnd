<?php

namespace PICOExplorer\Services\ServiceModels;

use PICOExplorer\Models\DataTransferObject;

interface PICOServiceEntryPoint
{

    public function get(DataTransferObject $DTO);

}
