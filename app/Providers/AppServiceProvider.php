<?php

namespace App\Providers;

use Debugbar;
use Response;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Blade;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Laravel\Dusk\DuskServiceProvider;
use App\View\Components\Form\Input\Checkbox;
use App\View\Components\Form\Input\Date;
use App\View\Components\Form\Input\Email;
use App\View\Components\Form\Input\File;
use App\View\Components\Form\Input\Password;
use App\View\Components\Form\Input\Radio;
use App\View\Components\Form\Input\Select;
use App\View\Components\Form\Input\Submit;
use App\View\Components\Form\Input\Text;
use App\View\Components\Form\Input\Textarea;
use App\View\Components\Form\Input\Time;
use Laravel\Telescope\TelescopeServiceProvider;
use Illuminate\Pagination\LengthAwarePaginator;
use Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider;
use Barryvdh\Debugbar\ServiceProvider as DebugbarServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        URL::forceScheme('https');
		$locale = config('app.locale');
		Carbon::setLocale($locale);
		setlocale(LC_TIME, $locale, 'de_DE.utf8', 'de');
        if('prod' !== config('app.env') && env('DEBUGBAR_ENABLED')) {
            Debugbar::enable();
        } else {
            Debugbar::disable();
        }
//		Paginator::defaultView('vendor.pagination.bootstrap-4');
        Paginator::useBootstrap();
        Schema::defaultStringLength(191); //NEW: Increase StringLength

        Blade::extend(function($value) {
            return preg_replace('/\{\?(.+)\?\}/', '<?php ${1} ?>', $value);
        });
		if (!Collection::hasMacro('paginate')) {
			Collection::macro('paginate',
				function ($perPage = 15, $page = null, $options = []) {
					$page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
					return (new LengthAwarePaginator(
						$this->forPage($page, $perPage), $this->count(), $perPage, $page, $options))
						->withPath('');
				});
		}

		Response::macro('attachment', function ($content, $type, $name) {

			$headers = [
				'Content-type'        => $type,
				'Content-Disposition' => 'attachment; filename="'.$name.'"',
			];

			return Response::make($content, 200, $headers);
		});
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(DuskServiceProvider::class);
		$this->app->bind('path.public', function() {
			return base_path('public_html');
		});

		if ($this->app->environment() !== 'prod') {
            $this->app->register(IdeHelperServiceProvider::class);
            $this->app->register(DebugbarServiceProvider::class);
			$this->app->register(TelescopeServiceProvider::class);
//            $this->app->register(\PrettyRoutes\ServiceProvider::class);
        }
    }
}
