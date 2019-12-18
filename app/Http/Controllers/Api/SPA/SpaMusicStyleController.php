<?php

namespace App\Http\Controllers\Api\SPA;

use App\Models\MusicStyle;
use App\Http\Resources\MusicStyleResource;
use App\Http\Controllers\Controller;
use App\Http\Requests\BandContactRequest;
use Carbon\Carbon;

class SpaMusicStyleController extends Controller
{
    public function all() {
        $result = MusicStyleResource::collection( MusicStyle::all()->sortBy('name') );
        return response()->json($result);
    }
}
