<?php

namespace App\Http\Controllers\Api\SPA;

use App\Models\Page;
use App\Repositories\PageRepository;
use App\Http\Controllers\Controller;
use App\Http\Resources\SpaPageResource;

class SpaPageController extends Controller
{
    /**
     * @var PageRepository
     */
    protected $repo;

    public function __construct()
    {
        $this->repo = new PageRepository();
        SpaPageResource::withoutWrapping();
    }

    public function routes()
    {
        $result = $this->repo->getRoutes();
        return response()->json($result);

    }

    public function pages()
    {
        $result = Page::whereIsPublished(1)->get();
        $result = SpaPageResource::collection($result);
        return response()->json($result);
    }

    public function page($slug)
    {
        $result = Page::whereSlug($slug)->whereIsPublished(1)->first();
        $result = SpaPageResource::make($result);
        return response()->json($result);
    }
}
