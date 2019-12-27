<?php

namespace App\Http\Controllers\Api\SPA;

use App\Http\Controllers\Controller;
use App\Repositories\MenuRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Response;

class SpaMenuController extends Controller
{
    /**
     * @var MenuRepository
     */
    protected $repo;

    public function __construct()
    {
        $this->repo = new MenuRepository();
    }

    public function tree()
    {
        $result = $this->repo->getPublishedTree();
        return response()->json($result);
    }

    public function top()
    {
        $result = $this->repo->getTopMenu(true);
        return response()->json($result);
    }

    public function bottom()
    {
        $result = $this->repo->getBottomMenu(true);
        return response()->json($result);
    }
}
