<?php

namespace Webefficiency\Http\Middleware;

use Closure;
use Session;

class CompanyMiddleware
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
        if (! Session::has('default_company')) {
            foreach ($request->user()->companies as $company) {
                if ($company->isDefault()) {
                    Session::put('default_company', $company->id);
                }
            }
        }

        if ($request->has('set_default_company') && intval($request->get('set_default_company')) > 0) {
            Session::set('default_company', $request->get('set_default_company'));
        }

        return $next($request);
    }
}
