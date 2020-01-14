<?php

namespace App\Http\Controllers\Api;

use App\Models\Event;
use App\Entities\EventEntity;
use App\Http\Controllers\Controller;
use App\Http\Resources\EventResource;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Cache;

class ApiEventController extends Controller
{
	/**
	 * @var Collection
	 */
	protected $actualEvents;

    public function __construct()
    {
        if (!Cache::has($this->cacheEventKey)) {
            Cache::put($this->cacheEventKey, Event::allActualMerged(), config('cache.ttl'));
        }
        $this->actualEvents = Cache::get($this->cacheEventKey, collect([]));
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function events($date = null)
    {
		EventResource::withoutWrapping();
		if($date) {
			/**
			 * @var $event EventEntity
			 */
			$event	= $this->actualEvents->get($date);
			$result	= EventResource($event);
		} else {
			$result = EventResource::collection($this->actualEvents->values());
		}
        return $result;
    }
}
