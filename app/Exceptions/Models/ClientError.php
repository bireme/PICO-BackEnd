<?php

namespace PICOExplorer\Services\AdvancedLogger\Exceptions\Models;

abstract class ClientError extends CustomException
{
    protected $code = 400;

}
