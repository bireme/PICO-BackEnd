<?php


namespace PICOExplorer\Exceptions\AbstractExceptions;

class InternalFatalException extends CustomException
{
    protected $ResponseCode = 500;
    protected $InternalError = true;
}
