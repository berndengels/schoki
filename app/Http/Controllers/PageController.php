<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Forms\BandsForm;
use App\Forms\NewsletterForm;
use Illuminate\Support\Facades\Request;
use Kris\LaravelFormBuilder\FormBuilder;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
//use Illuminate\Support\Facades\Request;

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
