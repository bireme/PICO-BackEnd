<?php


namespace PICOExplorer\Exceptions\AbstractExceptions;
use Exception;

class RegularException extends CustomException
{
    protected $ResponseCode = 400;
    protected $InternalError = false;
}
