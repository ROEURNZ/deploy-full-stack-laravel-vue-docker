<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class HandleInertiaRequests
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Logic to handle Inertia.js requests
        return $next($request);
    }
}
