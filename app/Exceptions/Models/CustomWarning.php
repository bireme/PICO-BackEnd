<?php

namespace PICOExplorer\Services\AdvancedLogger\Exceptions\Models;

abstract class CustomWarning
{
    protected $code;
    protected $message;
    protected $functionName;
    protected $file;
    protected $data;

    public function __construct(string $file,string $functionName,string $message=null, $data = null)
    {
        $this->file=$file;
        $this->functionName=$functionName;
        $this->message=$message;
        $this->data=$data;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function getData()
    {
        return $this->data;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function getLine()
    {
        return $this->functionName;
    }

    public function getFile()
    {
        return $this->file;
    }

}
