<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

/**
 * Class Cors
 * add Access-Control headers for API requests from outside (axios)
 * and handle the preflight request OPTION-method
 * @package App\Http\Middleware
 */
class Cors
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
		if($next($request) instanceof BinaryFileResponse) {
			return $next($request);
		}

		return $next($request)
			->header('Access-Control-Allow-Origin', '*')
			->header('Access-Control-Allow-Headers', '*')
			->header('Access-Control-Allow-Methods', 'HEAD, GET, POST, PUT, DELETE, OPTIONS')
			->header('X-Frame-Options', 'SAMEORIGIN', false)
			->header('P3P', 'CP="ALL DSP NID CURa ADMa DEVa HISa OTPa OUR NOR NAV DEM"')
			;
    }
}
?>