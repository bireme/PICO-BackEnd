<?php

namespace PICOExplorer\Exceptions\WarningsAndInfo\ClientWarnings;

use PICOExplorer\Exceptions\WarningsAndInfo\CustomWarning;

abstract class ClientWarning extends CustomWarning
{
    protected $code;
}
