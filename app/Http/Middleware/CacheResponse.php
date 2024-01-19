<?php

namespace App\Http\Middleware;

use Closure;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Silber\PageCache\Middleware\CacheResponse as BaseCacheResponse;

//class CacheResponse extends BaseCacheResponse
class CacheResponse
{
    public function handle(Request $request, Closure $next)
    {
        /**
         * @var $response Response
         */
//        $response = parent::handle($request, $next);
        $response = $next($request);
        $response->headers->set('cache-control', 'must-revalidate,public,max-age=3600');
        if($this->shouldCache($request, $response)) {
        }
        return $response;
    }

    protected function shouldCache(Request $request, Response $response)
    {
        // In this example, we don't ever want to cache pages if the
        // URL contains a query string. So we first check for it,
        // then defer back up to the parent's default checks.
        if ($request->getQueryString() && !app()->environment('prod')) {
            return false;
        }

//        return parent::shouldCache($request, $response);
    }
}
?>