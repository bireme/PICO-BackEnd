<?php


namespace PICOExplorer\Exceptions\AbstractExceptions;

class VisualException extends CustomException
{
    protected $ResponseCode = 200;
    protected $InternalError = false;
}
