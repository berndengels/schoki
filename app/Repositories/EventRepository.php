<?php

namespace App\Repositories;

use Carbon\Carbon;
use App\Models\Event;
use Illuminate\Database\Eloquent\Builder;

class EventRepository {

    public static function getEventsSinceToday() {
        return self::getEventsSinceDate(Carbon::today());
    }

	public static function getEventsSinceDate(Carbon $date) {
		$query = Event::whereDate('event_date','>=', $date)
			->orderBy('event_date')
		;
		return $query;
	}

	public static function getEventsUntilDate(Carbon $date, $search = null) {
		/**
		 * @var $query Builder
		 */
		$query = Event::whereDate('event_date','<=', $date);
		$result = $query->when($search, function($query) use ($search) {
			return $query
				->where('title','LIKE', "%${search}%")
				->orWhere('subtitle','LIKE', "%${search}%")
				->orWhere('description','LIKE', "%${search}%")
				;
		});
		return $result;
	}
}
