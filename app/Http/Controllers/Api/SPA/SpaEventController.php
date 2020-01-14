<?php

namespace App\Http\Controllers\Api\SPA;

use App\Models\Event;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use App\Http\Resources\SpaEventResource;
use Illuminate\Support\Facades\Response;

class SpaEventController extends Controller
{
	/**
	 * @var Collection
	 */
	protected $actualEvents;

    public function __construct()
    {
        SpaEventResource::withoutWrapping();
	}

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function events($date = null)
    {
        if (!Cache::has($this->apiCacheEventKey)) {
            Cache::put($this->apiCacheEventKey, Event::allActualMerged(), config('cache.ttl'));
        }
        $this->actualEvents = Cache::get($this->apiCacheEventKey, collect([]));

		if($date) {
			$event	= $this->actualEvents->get($date);
			$result	= SpaEventResource($event);
		} else {
			$result = SpaEventResource::collection($this->actualEvents->values());
		}

        return $result;
    }

    public function eventsByCategory($slug)
    {
        $cacheKey = $this->apiCacheEventCategoryKey . ucfirst( Str::camel($slug));

        if(!Cache::has($cacheKey)) {
            $data   = Event::mergedByCategorySlug($slug);
            $result = SpaEventResource::collection($data->values());
            Cache::put($cacheKey, $result, config('cache.ttl'));
        } else {
            $result = Cache::get($cacheKey, []);
        }

        return $result;
    }

    public function eventsByTheme($slug)
    {
        $cacheKey = $this->apiCacheEventThemeKey . ucfirst( Str::camel($slug));

        if(!Cache::has($cacheKey)) {
            $data   = Event::mergedByThemeSlug($slug);
            $result = SpaEventResource::collection($data->values());
            Cache::put($cacheKey, $result, config('cache.ttl'));
        } else {
            $result = Cache::get($cacheKey, []);
        }

        return $result;
    }
}
