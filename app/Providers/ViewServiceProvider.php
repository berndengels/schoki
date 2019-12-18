<?php
/**
 * ViewServiceProvider.php
 *
 * @author    Bernd Engels
 * @created   10.04.19 04:04
 * @copyright Webwerk Berlin GmbH
 */

namespace App\Providers;

use App\Http\View\Composer\GlobalsComposer;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Http\View\Composer\NavigationComposer;

class ViewServiceProvider extends ServiceProvider
{
	public function boot()
	{
		View::composer('*', GlobalsComposer::class);
		View::composer('*', NavigationComposer::class);
	}
}