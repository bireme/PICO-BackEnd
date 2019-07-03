<?php

namespace PICOExplorer\Exceptions\InternalErrors;

use PICOExplorer\Exceptions\AbstractExceptions\InternalFatalException;

class CantFetchRouteThrottleConfig extends InternalFatalException
{
    protected $langKey='CantFetchRouteThrottleConfig';
    protected $code='I1';
}
