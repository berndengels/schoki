<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\EventDescriptionResource;
use App\Models\Event;
use App\Entities\EventEntity;
use App\Http\Controllers\Controller;
use App\Http\Resources\EventResource;
use Illuminate\Support\Collection;
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
        if(!config('event.useCache')) {
            $this->actualEvents = Event::allActualMerged();
        } else {
            $this->actualEvents = Cache::remember($this->cacheEventKey, 3600, function() {
                return Event::allActualMerged();
            });
        }
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
			$result	= new EventResource($event);
		} else {
			$result = EventResource::collection($this->actualEvents->values());
		}
        return $result;
    }

    public function event(int $id)
    {
        EventResource::withoutWrapping();
        /**
         * @var $event EventEntity
         */
        $event  = $this->actualEvents->first(fn (EventEntity $e) => $e->getId() === $id);

        if($event) {
            $resource = new EventResource($event);
            return response()->json($resource);
        }

        return response()->json(['error'=> 'no data']);
    }

    public function eventDescriptionByDate(string $date)
    {
        EventDescriptionResource::withoutWrapping();
        /**
         * @var $event EventEntity
         */
        $event = $this->actualEvents->first(fn (EventEntity $e) => $e->getEventDate() === $date);

        if($event) {
            return response()->make($event->getDescriptionSanitized());
        }

        return response()->json(['error'=> 'no data']);
    }
}
