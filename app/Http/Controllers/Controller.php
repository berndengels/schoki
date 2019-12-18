<?php

namespace App\Http\Controllers;

use Jenssegers\Agent\Agent;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * @var null|Agent
     */
    public $agent = null;

    public function __construct()
    {
//        $this->middleware('web-public');
        $this->agent = new Agent();
    }
}
