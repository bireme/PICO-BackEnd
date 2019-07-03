<?php

namespace PICOExplorer\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Routing\Middleware\ThrottleRequests;
use PICOExplorer\Exceptions\InternalErrors\CantFetchRouteThrottleConfig;
use Illuminate\Support\Facades\Log;

class ThrottleBuilder
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $caller)
    {
        $config=config()->get('ThrottleMiddlewareConfig')??null;
        $callerconfig=$config[$caller]??null;
        $maxAttempts=$callerconfig['maxAttempts']??null;
        $decayMinutes=$callerconfig['decayMinutes']??null;
        $ip = '['.$request->getClientIp().']: ';
        Log::channel('UserRequests')->info($ip.json_encode($request->json()->all()));
        Log::channel('UserIps')->info($request->getClientIp());
        if(!($maxAttempts && $maxAttempts)){
            throwException(new CantFetchRouteThrottleConfig(['caller'=>$caller]));
        }
        return app(ThrottleRequests::class)->handle($request, $next, $maxAttempts, $decayMinutes);
    }

}
