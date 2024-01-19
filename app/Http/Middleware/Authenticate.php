<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  Request  $request
     * @return string
     */
/*
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            return route('admin.eventList');
        }
    }
*/
	protected function authenticate($request, array $guards)
	{
		if (empty($guards)) {
			$guards = [null];
		}

		foreach ($guards as $guard) {
			if ($this->auth->guard($guard)->check()) {
				return $this->auth->shouldUse($guard);
			}
		}

		$this->unauthenticated($request, $guards);
	}

	public function handle($request, Closure $next, ...$guards)
	{
//		dd($request->user());
		$this->authenticate($request, $guards);

		return $next($request);
	}

	protected function unauthenticated($request, array $guards)
	{
		throw new AuthenticationException(
			'Unauthenticated.', $guards, $this->redirectTo($request)
		);
	}
}
?>