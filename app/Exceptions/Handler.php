<?php

namespace PICOExplorer\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Exception;
use PICOExplorer\Http\Traits\ExceptionLogBuilderTrait;
use Illuminate\Contracts\Debug\ExceptionHandler as ExceptionHandlerContract;

class Handler extends ExceptionHandler implements ExceptionHandlerContract
{
    use ExceptionLogBuilderTrait;

    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    public function report(Exception $e)
    {

    }

    public function render($request, Exception $ex)
    {
        $CustomExceptionTypes = [
            'AppErrors' => ['channel' => 'AppDebug', 'level' => 'error', 'IpPath' => 1, 'ReqContent' => 1, 'Headers' => 0],
            'Emergency' => ['channel' => 'Emergency', 'level' => 'emergency', 'IpPath' => 1, 'ReqContent' => 1, 'Headers' => 1],
            'ClientErrors' => ['channel' => 'ClientDebug', 'level' => 'error', 'IpPath' => 1, 'ReqContent' => 1, 'Headers' => 1],
        ];
        $ExtraExceptionTypes = [
            'ValidationException' => ['channel' => 'AppDebug', 'level' => 'warning', 'IpPath' => 1, 'ReqContent' => 0, 'Headers' => 0],
            'AuthenticationException' => ['channel' => 'ClientDebug', 'level' => 'error', 'IpPath' => 1, 'ReqContent' => 1, 'Headers' => 1],
            'HttpResponseException' => ['channel' => 'AppDebug', 'level' => 'error', 'IpPath' => 1, 'ReqContent' => 1, 'Headers' => 1],
        ];

        try {
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
            $this->ExceptionLogBuilder($ex,$channel,$level);
        } catch (Exception $newex) {
            //
        }finally{
            return parent::render($request, $ex);
        }
    }

}
