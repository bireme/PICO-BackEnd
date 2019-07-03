<?php


namespace PICOExplorer\Exceptions\AbstractExceptions;

use Exception;
use Throwable;

class CustomException extends Exception
{
    protected $langKey='Unassigned';
    protected $code=0;
    protected $ResponseCode = null;
    protected $InternalError=false;

    public function __construct($data = null, Throwable $previous = null)
    {
        $Internals= trans('Internals');
        $ServerTxt = trans('Server',[],'en');
        $message='';
        if($this->InternalError){
            if(array_key_exists($this->langKey, $ServerTxt)){
                $message = $ServerTxt[$this->langKey];
            }else{
                $InternalError=false;
            }
        }
        if(!($InternalError)){
            if(array_key_exists($this->langKey, $Internals)){
                $message = $Internals[$this->langKey];
            }else{
                $message = $ServerTxt['KeyNotExist'];
            }
        }

        if ($this->ResponseCode) {
            $message = $this->ResponseCode . ' | ' . $message;
        }
        if ($data) {
            $message = $message . ': ' .json_encode($data);
        }
        parent::__construct($message, $this->code, $previous);
    }

}
