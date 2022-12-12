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
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();
//        $this->mapWebRoutes();
        $this->mapPublicRoutes();
        $this->mapAdminRoutes();
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
/*
    protected function mapWebRoutes()
    {
        Route::middleware('web')
             ->namespace($this->namespace)
             ->group(base_path('routes/web.php'))
        ;
    }
*/

    protected function mapPublicRoutes()
    {
        Route::middleware('public')
            ->namespace($this->namespace)
            ->group(base_path('routes/public.php'))
        ;
    }

    protected function mapAdminRoutes()
    {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/admin.php'))
        ;
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::prefix('api')
             ->middleware('api')
             ->namespace($this->namespace . '\Api')
             ->group(base_path('routes/api.php'));
    }
}
