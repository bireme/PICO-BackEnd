<?php

namespace PICOExplorer\Http\Middleware;

use Closure;
use PICOExplorer\Http\Traits\ClientRequestDataTrait;
use PICOExplorer\Http\Traits\AdvancedLoggerTrait;


class ConnectionsLogger
{

    use AdvancedLoggerTrait;
    use ClientRequestDataTrait;

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $ClientInfo = $this->ClientInfo();

        $Conntitle = $ClientInfo['ip'];
        $Coninfo = $ClientInfo['url'];
        $optitle = '[' . $Conntitle . '] ' . $Coninfo;
        $dataArray = [
            'content' => $ClientInfo['content'],
            'headers' => $ClientInfo['headers'],
        ];

        $this->AdvancedLog('Connections', 'info', $Conntitle, $Coninfo, null, null);
        $this->AdvancedLog('Operations', 'info', $optitle, null, $dataArray, null);
        return $next($request);
    }


}
