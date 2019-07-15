<?php

namespace PICOExplorer\Http\Traits;

use Throwable;
use PICOExplorer\Exceptions\CustomException;

trait ExceptionBaseData
{


    protected function getExceptionBaseData(Throwable $exception, bool $isPrevious)
    {
        if ($exception instanceof CustomException) {
            $premessage = $this->getMessageAndDataCustomException($exception);
        } else {
            $premessage = $this->getMessageAndDataBasicException($exception);
        }
        $trace = null;
        if (!($isPrevious)) {
            $trace = $this->ProcessTrace($exception->getTraceAsString());
        }
        return [
            'title' => get_class($exception),
            'slug' => $this->getTranslatedMessage(get_class($exception), $exception instanceof CustomException),
            'code' => $exception->getCode(),
            'message' => $premessage['message'],
            'data' => $premessage['data'],
            'line' => $exception->getLine(),
            'file' => $exception->getFile(),
            'trace' => $trace,
        ];
    }

    protected function getTranslatedMessage(string $ErrorKey, $isCustomError = true)
    {
        $tmp = explode('\\', $ErrorKey);
        $ErrorKey = end($tmp);
        $Internals = trans('Internals', [], 'en');
        $ServerTxt = trans('Server', [], 'en');
        if (array_key_exists($ErrorKey, $ServerTxt)) {
            return $ServerTxt[$ErrorKey];
        } elseif (array_key_exists($ErrorKey, $Internals)) {
            return $Internals[$ErrorKey];
        }
        if ($isCustomError) {
            return '[LANGTRANS KEY NOT EXIS FIX THIS] - ' . $ErrorKey;
        } else {
            return false;
        }
    }

    protected function getMessageAndDataCustomException(CustomException $exception)
    {
        $premessage = json_decode($exception->getMessage(), true);
        $premessage['message'] = null;
        return $premessage;
    }

    protected function getMessageAndDataBasicException(Throwable $exception)
    {
        $message = $exception->getMessage();
        $data = null;
        return ['message' => $message, 'data' => $data];
    }

    protected function ProcessTrace(string $TraceAsString){
        $Tracetxt = explode('#',$TraceAsString);
        $TracerArr=array();
        $vendorCount = 0;
        $illuminateCount = 0;
        $totalcount=0;
        foreach ($Tracetxt as $txt) {
            if(strlen($txt)<3 || $totalcount>5){
                continue;
            }
            if (strpos($txt, '\\') === false) {
                continue;
            }
            if (strpos($txt, 'vendor') === false) {
                if (strpos($txt, 'Illuminate') === false) {
                    array_push($TracerArr, $this->ProcessStackLine($txt));
                    $totalcount++;
                }elseif($illuminateCount<3){
                    array_push($TracerArr, $this->ProcessStackLine($txt));
                    $illuminateCount++;
                    $totalcount++;
                }
            }elseif($vendorCount<1){
                array_push($TracerArr, $this->ProcessStackLine($txt));
                $vendorCount++;
                $totalcount++;
            }
        }
        return PHP_EOL.'Stack Report:'.PHP_EOL.join('',$TracerArr);
    }

    protected function ProcessStackLine(string $txt){
        $Tracetxt = explode('):',$txt);
        if(count($Tracetxt)>1){
            $newtxt=$Tracetxt[1];
            $tmp = explode('\\',$Tracetxt[0]);
            if(count($tmp)>1){
                $offset = min(3,count($tmp));
                $tmp3 ='...\\'. join('\\',array_slice($tmp, -$offset, $offset, true));
                $tmp2 = explode(' ',reset($tmp))[0].$tmp3.'):';
            }else{
                $tmp2 = explode(' ',reset($tmp))[0].'):';
            }
            $newtxt = $tmp2. $newtxt;
        }else{
            $newtxt=$txt;
        }
        return '#'.$newtxt;
    }


}
