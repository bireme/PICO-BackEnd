<?php

namespace PICOExplorer\Services\AdvancedLogger\Services;

use PICOExplorer\Exceptions\Exceptions\AppError\ErrorContactingAnotherComponent;
use PICOExplorer\Facades\ExceptionLoggerFacade;

class ServiceComponentExceptionHub
{

 public function index(string $caller,string $target,\Throwable $Ex){
     ExceptionLoggerFacade::ExceptionLogBuilder($Ex,'AppDebug','error');
 }

}
