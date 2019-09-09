<?php

namespace PICOExplorer\Http\Middleware;

use Closure;
use PICOExplorer\Facades\AuthHandlerFacade;

class IsAdmin
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
        $adminAuth = AuthHandlerFacade::isAdmin();
        if ($adminAuth > 0) {
            return $next($request);
        }
        return redirect()->route('auth.adminlogin.login',[],302);
    }

}
