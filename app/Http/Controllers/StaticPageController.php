<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Contracts\View\Factory;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;

/**
 * Class PageController
 */
class StaticPageController extends BaseController
{
    use ValidatesRequests;

	/**
	 * @param $page
	 * @return Factory|View
	 */
    public function get() {
        $route  = Route::currentRouteName();
        $page	= collect(explode('.', $route))->last();
        $view   = view('public.static.' . $page, ['data' => null ]);

        if( file_exists($view->getPath()) ) {
            return $view;
        }

        return null;
    }
}
