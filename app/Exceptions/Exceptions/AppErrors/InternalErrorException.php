<?php

namespace PICOExplorer\Exceptions\Exceptions\AppErrors;

use PICOExplorer\Exceptions\Exceptions\CustomErrorException;

abstract class InternalErrorException extends CustomErrorException
{
    protected $code = 500;

}
