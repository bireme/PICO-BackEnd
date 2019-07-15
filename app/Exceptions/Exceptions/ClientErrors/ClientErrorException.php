<?php

namespace PICOExplorer\Exceptions\Exceptions\ClientErrors;

use PICOExplorer\Exceptions\Exceptions\CustomErrorException;

abstract class ClientErrorException extends CustomErrorException
{
    protected $code = 400;

}
