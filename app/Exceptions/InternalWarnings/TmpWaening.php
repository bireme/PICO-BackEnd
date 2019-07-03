<?php

namespace PICOExplorer\Exceptions\InternalWarnings;

use PICOExplorer\Exceptions\AbstractExceptions\InternalWarningException;

class TmpWaening extends InternalWarningException
{
    protected $langKey='CantFetchRouteThrottleConfig';
    protected $code='I1';
}
