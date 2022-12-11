<?php

namespace App\Repositories;

use Carbon\Carbon;
use App\Models\Event;
use Illuminate\Database\Eloquent\Builder;

class EventRepository  extends MainEventRepository {

    public static function getEventsSinceToday() {
        return self::getEventsSinceDate(Carbon::today());
    }

	public static function getEventsSinceDate(Carbon $date) {
		$query = Event::whereDate('event_date','>=', $date)
            ->sortable()
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
                ->whereRaw("MATCH(title) AGAINST('$search')")
                ->orWhereRaw("MATCH(subtitle) AGAINST('$search')")
                ->orWhereRaw("MATCH(description) AGAINST('$search')")
                ->sortable();
		});
		return $result;
	}
}
