<?php


namespace PICOExplorer\Exceptions\AbstractExceptions;

class WarningException extends CustomException
{
    protected $ResponseCode = 200;
    protected $InternalError = false;
}
