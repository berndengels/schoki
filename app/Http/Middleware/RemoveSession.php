<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Session\Middleware\StartSession as Middleware;

class RemoveSession extends Middleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Request $request
     * @param Closure $next
     * @return mixed
     */

    public function handle($request, Closure $next)
    {
        config()->set('session.driver', 'array');
        return $next($request);
    }
}
?>