<?php

namespace App\Http\Controllers\Api\SPA;

use Carbon\Carbon;
use App\Models\Event;
use App\Entities\EventEntity;
use App\Http\Controllers\Controller;
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
//        $this->middleware('auth:api')->except(['events']);
		$this->actualEvents = Event::allActualMerged();
        SpaEventResource::withoutWrapping();
	}

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function events($date = null)
    {
		if($date) {
			/**
			 * @var $event EventEntity
			 */
			$event	= $this->actualEvents->get($date);
			$result	= SpaEventResource($event);
		} else {
			$result = SpaEventResource::collection($this->actualEvents->values());
		}
        return $result;
    }

    public function eventsByCategory($slug)
    {
        $data = Event::mergedByCategorySlug($slug);
        $result = SpaEventResource::collection($data->values());
        return $result;
    }

    public function eventsByTheme($slug)
    {
        $data = Event::mergedByThemeSlug($slug);
        $result = SpaEventResource::collection($data->values());
        return $result;
    }
}
