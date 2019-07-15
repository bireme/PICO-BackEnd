<?php


namespace PICOExplorer\Exceptions;

use Exception;

use Throwable;

abstract class CustomException extends Exception
{
    protected $code;

    public function __construct($data = null, Throwable $previous = null)
    {
        $message=json_encode(['data' => $data]);
        parent::__construct($message, $this->code, $previous);
    }

}
