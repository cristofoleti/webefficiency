<?php

namespace Webefficiency\Http\Middleware;

use Closure;

class GroupAdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (\Auth::user()->isGroupAdmin())
        {
            return $next($request);
        }

        return 'Acesso negado';
    }
}
