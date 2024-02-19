<?php

namespace App\Providers;

//use PaginateRoute;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';
	protected $apiNamespace = 'App\Http\Controllers\Api';
//	protected $apiSpaNamespace = 'App\Http\Controllers\Api\SPA';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        Route::pattern('id', '[0-9]+');
        Route::pattern('date', '^[12][0-9]{3}\-[0-9]{2}\-[0-9]{2}$');
        Route::pattern('year', '[2][0-9]{3}');
        Route::pattern('month', '[0-1][0-9]');
//        PaginateRoute::registerMacros();
        parent::boot();

		$this->routes(function () {
			Route::middleware('api')
				->namespace($this->apiNamespace)
				->prefix('api')
				->group(base_path('routes/api.php'));

			Route::middleware('web')
				->namespace($this->namespace)
				->group(base_path('routes/web.php'));
		});
    }
}
