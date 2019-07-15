<?php


namespace PICOExplorer\Http\Controllers;


use Illuminate\Routing\Middleware\ThrottleRequests;

trait ThrottleMiddlewareTrait
{

    protected static $userRepository;

    public function withThrottleMiddleware(string $caller)
    {

    }
}
