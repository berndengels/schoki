<?php

namespace App\Libs;

use App\Repositories\PageRepository;
use Route;

/**
 * App\Libs\Routes
 */
class Routes
{
	/**
	 * @var array
	 */
	public static $routes = [];

	public static function getRoutes( $prefix = null )
	{
		$routes = collect(Route::getRoutes()->getRoutesByName());

		if( $prefix ) {
			$routes = $routes->reject(function ($value, $key) use ($prefix) {
				return !preg_match("#$prefix#", $key);
			});
		}

		foreach($routes as $k => $r) {
			if( !preg_match("/\{[^\}]+\}/", $r->uri) ) {
				$uri = '/'.$r->uri;
				self::$routes[$uri] = $uri;
			}
		}

		self::$routes = collect(self::$routes);
		return self::$routes;
	}

	public static function getPageRoutes()
	{
		return collect(PageRepository::getRoutes());
	}

	public static function getPublicRoutes()
	{
		return self::getPageRoutes()->merge(self::getRoutes('public'));
	}
}
