<?php


namespace PICOExplorer\Exceptions\AbstractExceptions;

class InternalWarningException extends CustomException
{
    protected $ResponseCode = 500;
    protected $InternalError = true;
}
