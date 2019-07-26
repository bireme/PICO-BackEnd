<?php

namespace PICOExplorer\Services\AdvancedLogger\Middleware;

use Closure;
use PICOExplorer\Facades\AdvancedLoggerFacade;

class ConnectionsLogger
{

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        AdvancedLoggerFacade::LogConnectionInfo();
        return $next($request);
    }


}
