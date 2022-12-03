<?php

namespace App\Http\Controllers;

use Jenssegers\Agent\Agent;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

/**
 * Class Controller
 * @package App\Http\Controllers
 */
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * @var null|Agent
     */
    public $agent = null;
    /**
     * @var string
     */
    protected $cacheEventKey = 'events';
    /**
     * @var string
     */
    protected $cacheEventCategoryKey = 'eventsByCategory';
    /**
     * @var string
     */
    protected $cacheEventThemeKey = 'eventsByTheme';
    protected $cacheTopMenuKey = 'topMenu';
    protected $cacheBottomMenuKey = 'bottomMenu';

    /**
     * @var string
     */
    protected $apiCacheEventKey = 'apiEvents';
    /**
     * @var string
     */
    protected $apiCacheEventCategoryKey = 'apiEventsByCategory';
    /**
     * @var string
     */
    protected $apiCacheEventThemeKey = 'apiEventsByTheme';

    /**
     * Controller constructor.
     */
    public function __construct()
    {
//        $this->middleware('web-public');
        $this->agent = new Agent();
    }
}
