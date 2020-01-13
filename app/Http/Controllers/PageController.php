<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Http\Controllers\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;

class PageController extends BaseController
{
    use ValidatesRequests;

    public function get($slug) {
        /**
         * @var Page $data
         */
        $data = Page::where('slug','=', $slug)->firstOrFail();
        return view('public.page', ['data' => $data ]);
    }
}
