<?php

namespace App\Providers;

use Carbon\Carbon;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
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

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
		$locale = config('app.locale');
		Carbon::setUTF8(true);
		Carbon::setLocale($locale);
		setlocale(LC_TIME, $locale, 'de_DE.utf8', 'de');

		Paginator::defaultView('vendor.pagination.bootstrap-4');
        Schema::defaultStringLength(191); //NEW: Increase StringLength

        Blade::component('components.alert', 'alert');
        Blade::component('inp.text', Text::class);
        Blade::component('inp.email', Email::class);
        Blade::component('inp.date', Date::class);
        Blade::component('inp.time', Time::class);
        Blade::component('inp.file', File::class);
        Blade::component('inp.password', Password::class);
        Blade::component('inp.checkbox', Checkbox::class);
        Blade::component('inp.radio', Radio::class);
        Blade::component('inp.submit', Submit::class);
        Blade::component('inp.textarea', Textarea::class);
        Blade::component('inp.select', Select::class);

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

		if(env('REDIRECT_HTTPS')) {
			URL::forceScheme('https');
		}
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
			$this->app->register(TelescopeServiceProvider::class);
            $this->app->register(\PrettyRoutes\ServiceProvider::class);
        }
    }
}
