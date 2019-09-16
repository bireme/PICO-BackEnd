<?php

namespace PICOExplorer\Services\AdvancedLogger\Exceptions\Models;

abstract class AppError extends CustomException
{
    protected $code = 500;

}
