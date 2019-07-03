<?php

namespace PICOExplorer\Exceptions;

use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use PICOExplorer\Exceptions\AbstractExceptions\CustomException;
use PICOExplorer\Exceptions\AbstractExceptions\InfoException;
use PICOExplorer\Exceptions\AbstractExceptions\InternalFatalException;
use PICOExplorer\Exceptions\AbstractExceptions\InternalWarningException;
use PICOExplorer\Exceptions\AbstractExceptions\RegularException;
use PICOExplorer\Exceptions\AbstractExceptions\VisualException;
use PICOExplorer\Exceptions\AbstractExceptions\WarningException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param \Exception $exception
     * @return void
     */

    /**
     * Render an exception into an HTTP response.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Exception $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        if ($exception instanceof InternalWarningException) {
            return $this->RenderErrorMessage($request, $exception, 'InternalWarning');
        }
        if ($exception instanceof InternalFatalException) {
            return $this->RenderErrorMessage($request, $exception, 'InternalFatal');
        }
        if ($exception instanceof RegularException) {
            return $this->RenderErrorMessage($request, $exception, 'ClientErrors');
        }
        if ($exception instanceof WarningException) {
            return $this->RenderErrorMessage($request, $exception, 'Warning');
        }
        if ($exception instanceof InfoException) {
            return $this->RenderErrorMessage($request, $exception, 'Info');
        }
        if ($exception instanceof VisualException) {
            return $this->RenderErrorMessage($request, $exception, 'View');
        }
        return $this->RenderUnknownError($request, $exception);
        //return parent::render($request, $exception);
    }


    protected function RenderUnknownError($request, Exception $exception)
    {
        $ip = '['.$request['ip'].']: ';
        $code = $exception->getCode();
        $msgplus = $code . ' | ' .  $exception->getMessage();
        $line = ' @ ' .$exception->getLine().': '.$exception->getFile();
        $trace = $exception->getTraceAsString();
        $this->getLogChannel('UnknownErrors')->error($ip.$msgplus.$line.$trace);
        return parent::render($request, $exception);
    }

    protected function RenderErrorMessage($request,CustomException $exception, $type)
    {
        $predata = explode(" | ", $exception->getMessage());
        if (count($predata) > 1) {
            $responsecode = $predata[0];
            $message = $predata[1];
        } else {
            $message = $predata[0];
            $responsecode = null;
        }
        $code = $exception->getCode();
        $line = ' @ ' .$exception->getLine().': '.$exception->getFile();
        $trace = $exception->getTraceAsString();
        $ip = '['.$request['ip'].']: ';
        $msgplus = $code . ' | ' . $message;
        switch ($type) {
            case 'InternalFatal':
                $this->getLogChannel($type)->error($ip.$msgplus.$line.$trace);
                return abort($responsecode ?? 500);
            case 'InternalWarning':
                $this->getLogChannel($type)->warning($msgplus.$line);
                return false;
            case 'ClientErrors':
                $this->getLogChannel($type)->error($ip.$msgplus.$line.$trace);
                return Response()->json([
                    'Error' => $message
                ], $responsecode ?? 400);
            case 'Warning':
                $this->getLogChannel($type)->warning($ip.$msgplus.$line);
            case 'Info':
                session()->put('warning', [$type => $message]);
                return false;
            case 'View':
                $this->getLogChannel($type)->warning($msgplus.$line);
                return view($responsecode)->with(['message' => $msgplus]);
            default:
                $this->getLogChannel($type)->error($msgplus.$line.$trace);
                return Response()->json([
                    $type => $msgplus
                ], $responsecode ?? 400);
        }
    }

    protected function getLogChannel($channel){
        return Log::channel($channel);
    }


}
