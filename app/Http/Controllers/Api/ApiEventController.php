<?php

namespace App\Http\Controllers\Api;

use App\Models\Event;
use App\Entities\EventEntity;
use App\Http\Controllers\Controller;
use App\Http\Resources\EventResource;
use Carbon\Carbon;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;

class ApiEventController extends Controller
{
	/**
	 * @var Collection
	 */
	protected $actualEvents;

    public function __construct()
    {
//        $this->middleware('auth:api')->except(['events']);
		$this->actualEvents = Event::allActualMerged();
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
