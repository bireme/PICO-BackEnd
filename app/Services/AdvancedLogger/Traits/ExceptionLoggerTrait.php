<?php


namespace PICOExplorer\Services\AdvancedLogger\Traits;

use PICOExplorer\Services\AdvancedLogger\Exceptions\Models\CustomException;
use Throwable;

trait ExceptionLoggerTrait
{

    public function ReportException(Throwable $ex)
    {
        $CustomExceptionTypes = [
            'AppError' => ['channel' => 'AppDebug', 'level' => 'error', 'IpPath' => 1, 'ReqContent' => 1, 'Headers' => 0],
            'Emergency' => ['channel' => 'Emergency', 'level' => 'emergency', 'IpPath' => 1, 'ReqContent' => 1, 'Headers' => 1],
            'ClientError' => ['channel' => 'ClientDebug', 'level' => 'error', 'IpPath' => 1, 'ReqContent' => 1, 'Headers' => 1],
        ];
        $ExtraExceptionTypes = [
            'ValidationException' => ['channel' => 'AppDebug', 'level' => 'warning', 'IpPath' => 1, 'ReqContent' => 0, 'Headers' => 0],
            'AuthenticationException' => ['channel' => 'ClientDebug', 'level' => 'error', 'IpPath' => 1, 'ReqContent' => 1, 'Headers' => 1],
            'HttpResponseException' => ['channel' => 'AppDebug', 'level' => 'error', 'IpPath' => 1, 'ReqContent' => 1, 'Headers' => 1],
        ];

        $ExInfo = null;
        if (is_a($ex, 'CustomException')) {
            foreach ($CustomExceptionTypes as $BaseType => $BaseInfo) {
                if (is_a($ex, $BaseType)) {
                    $ExInfo = $BaseInfo;
                    break;
                }
            }
        } else {
            foreach ($ExtraExceptionTypes as $BaseType => $BaseInfo) {
                if (is_a($ex, $BaseType)) {
                    $ExInfo = $BaseInfo;
                    break;
                }
            }
        }

        $channel = $ExInfo ? ($ExInfo['channel']) : ('InternalErrors');
        $level = $ExInfo ? ($ExInfo['level']) : ('critical');
        $this->ExceptionLogBuilder($ex, $channel, $level);

    }

    public function ExceptionLogBuilder(Throwable $exception, string $channel, string $level)
    {
        $res = false;
        $MainData = null;
        try {
            $this->MainLoggerData($exception, $MainData);
            $this->AdvancedLog($channel, $level, $MainData);
            $res = true;
        } catch (Throwable $LogError) {
            $this->AuxiliarLog($LogError, 'Emergency', 'critical', true);
        } finally {
            if ($res === false) {
                $this->AuxiliarLog($exception, $channel, $level, false, $MainData);
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
        if(is_array($premessage)) {
            if (array_key_exists('data', $premessage) || array_key_exists('message', $premessage)) {
                $message = $premessage['message'] ?? null;
                $data = $premessage['data'] ?? null;
            } else {
                $message=json_encode($premessage);
                $data=null;
            }
        }else{
            $message=$premessage;
            $data=null;
        }
        $trace = ($exception->getTraceAsString()) ?? null;
        $trace = strlen($trace) ? $trace : null;
        return [
            'title' => get_class($exception),
            'slug' => ($exception instanceof CustomException),
            'code' => $exception->getCode(),
            'message' => $message,
            'data' => $data,
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
            $message = (strlen($message)) ? ($message) : (null);
            $data = null;
            return ['message' => $message, 'data' => $data];
        }
    }

}
