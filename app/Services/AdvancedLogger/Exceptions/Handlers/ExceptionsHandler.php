<?php

namespace PICOExplorer\Services\AdvancedLogger\Exceptions\Handlers;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Exception;
use Illuminate\Contracts\Debug\ExceptionHandler as ExceptionHandlerContract;
use PICOExplorer\Services\AdvancedLogger\Exceptions\DontCatchException;
use PICOExplorer\Services\AdvancedLogger\Traits\ExceptionLoggerTrait;

class ExceptionsHandler extends ExceptionHandler implements ExceptionHandlerContract
{

    use ExceptionLoggerTrait;

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
        try {
            $this->ReportException($ex);
        } catch (DontCatchException $newex) {
            //
        } finally {
            return parent::render($request, $ex);
        }

    }

}
