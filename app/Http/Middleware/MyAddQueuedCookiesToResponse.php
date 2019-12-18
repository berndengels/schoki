<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Cookie\QueueingFactory as CookieJar;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Http\Request;

class MyAddQueuedCookiesToResponse extends AddQueuedCookiesToResponse
{
    protected $except = [
//        'events/*',
//        'page/*',
//        'static/*',
    ];

    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        if (count($this->except) > 0) {
            foreach ($this->except as $except) {
                if($request->is($except)) {
                    return $response;
                }
            }
        }

        foreach ($this->cookies->getQueuedCookies() as $cookie) {
            $response->headers->setCookie($cookie);
        }

        return $response;
    }
}
