<?php

namespace App\Http\Middleware;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;
use Symfony\Component\HttpFoundation\Cookie;

class VerifyCsrfToken extends Middleware
{
	/**
	 * The URIs that should be excluded from CSRF verification.
	 *
	 * @var array
	 */
	protected $except = [
		'events/*',
		'page/*',
		'static/*',
		'contact/*',
		'login',
	];

    /**
     * Indicates whether the XSRF-TOKEN cookie should be set on the response.
     *
     * @var bool
     */
    protected $addHttpCookie = true;

	protected function addCookieToResponse($request, $response)
	{
		$config = config('session');

		if ($response instanceof Responsable) {
			$response = $response->toResponse($request);
		}

		$response->headers->setCookie(
			new Cookie(
				'XSRF-TOKEN', $request->session()->token(), $this->availableAt(60 * $config['lifetime']),
				$config['path'], $config['domain'], $config['secure'], false, false, $config['same_site'] ?? null
			)
		);

		return $response;
	}
}
?>