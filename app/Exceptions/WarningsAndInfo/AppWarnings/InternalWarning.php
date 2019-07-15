<?php

namespace PICOExplorer\Exceptions\WarningsAndInfo\AppWarnings;

use PICOExplorer\Exceptions\WarningsAndInfo\CustomWarning;

abstract class InternalWarning extends CustomWarning
{
    protected $code;
}
