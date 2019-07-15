<?php

namespace PICOExplorer\Exceptions\Exceptions;

use PICOExplorer\Exceptions\CustomException;

abstract class CustomErrorException extends CustomException implements InterfaceCustomError
{
    protected $code;
}
