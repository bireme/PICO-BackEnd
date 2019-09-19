<?php

namespace PICOExplorer\Services\ServiceModels;

use PICOExplorer\Models\DataTransferObject;

interface ServiceEntryPointInterface
{

    public function get(DataTransferObject $DTO);

}
