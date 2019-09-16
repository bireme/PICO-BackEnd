<?php


namespace PICOExplorer\Services\AdvancedLogger\Exceptions\Models;

use Exception;

use PICOExplorer\Services\AdvancedLogger\Exceptions\Interfaces\InterfaceCustomError;
use Throwable;

abstract class CustomException extends Exception implements InterfaceCustomError
{
    protected $code=500;

    public function __construct($data = null, Throwable $previous = null)
    {
        $message='';
        if($data){
            $message=json_encode(['data' => $data]);
        }
        parent::__construct($message, $this->code, $previous);
    }

}
