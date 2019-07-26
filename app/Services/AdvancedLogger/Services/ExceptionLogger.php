<?php


namespace PICOExplorer\Services\AdvancedLogger\Services;


use PICOExplorer\Services\AdvancedLogger\AdvancedLogger;
use PICOExplorer\Services\AdvancedLogger\Exceptions\Models\CustomException;
use Throwable;

class ExceptionLogger extends AdvancedLogger
{
    public function ExceptionLogBuilder(Throwable $exception, string $channel, string $level)
    {
        $res = false;
        $MainData = null;
        try {
            $this->MainLoggerData($exception, $MainData);
            $this->AdvancedLog($channel, $level, $MainData);
            $res = true;
        } catch (Throwable $LogError) {
            $this->AuxiliarLog($LogError,'Emergency','critical',true);
        } finally {
            if ($res === false) {
                $this->AuxiliarLog($exception,$channel,$level,false,$MainData);
            }
        }
    }

    protected function MainLoggerData(Throwable $exception, &$currentdata)
    {
        $currentdata = $this->getExceptionBaseData($exception);
        $currentdata['reqdata'] = $this->ClientData();
        $previous = $exception->getPrevious();
        $currentdata['previousdata'] = null;
        if ($previous) {
            $currentdata['previousdata'] = ($this->getExceptionBaseData($previous)) ?? null;
        }
    }

    protected function getExceptionBaseData(Throwable $exception)
    {
        $premessage = $this->getMessageAndData($exception);
        $trace = ($exception->getTraceAsString())??null;
        $trace = strlen($trace)?$trace:null;
        return [
            'title' => get_class($exception),
            'slug' => ($exception instanceof CustomException),
            'code' => $exception->getCode(),
            'message' => $premessage['message'],
            'data' => $premessage['data'],
            'line' => $exception->getLine(),
            'file' => $exception->getFile(),
            'trace' => $trace,
        ];
    }

    protected function getMessageAndData(Throwable $exception)
    {
        if ($exception instanceof CustomException) {
            $premessage = json_decode($exception->getMessage(), true);
            $premessage['message'] = null;
            return $premessage;
        } else {
            $message = $exception->getMessage();
            $message = (strlen($message))?($message):(null);
            $data = null;
            return ['message' => $message, 'data' => $data];
        }
    }
}
