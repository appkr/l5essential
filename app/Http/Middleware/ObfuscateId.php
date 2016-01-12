<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ObfuscateId
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     * @param string|null              $param
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $param = null)
    {
        $routeParamName = $param ? str_plural($param) : 'id';

        if ($routeParamValue = $request->route()->getParameter($routeParamName)) {
            $request->route()->setParameter($routeParamName, optimus()->decode($routeParamValue));
        }

        return $next($request);
    }
}
