<?php


namespace PICOExplorer\Exceptions\AbstractExceptions;

class InfoException extends CustomException
{
    protected $ResponseCode = 200;
    protected $InternalError = false;
}
