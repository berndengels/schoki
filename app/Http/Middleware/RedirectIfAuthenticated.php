<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
//			return redirect('/');
			return redirect($this->redirectTo());
        }

        return $next($request);
    }

    protected function redirectTo() {
        return '/admin/events';
    }
}
?>