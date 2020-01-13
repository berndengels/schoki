<?php

namespace App\Repositories;

use Carbon\Carbon;
use App\Models\Event;
use App\Libs\EventDateTime;
use App\Entities\EventEntity;
use App\Models\EventPeriodic;
use App\Repositories\EventEntityRepository;
use Illuminate\Support\Facades\Cache;

class EventPeriodicRepository {

    protected $eventDateTime = null;
	private $cacheTTL = 3600 * 2; // 3 hours

    public function __construct()
    {
        $this->eventDateTime = new EventDateTime();
    }

    public function getPeriodicDates( EventPeriodic $entity, $formated = true, $isPublic = false )
	{
        $dates = $this->eventDateTime->getPeriodicEventDates($entity->periodic_position, $entity->periodic_weekday, $formated, $isPublic);
		return $dates;
    }

    public function getAllPeriodicDates($formated = true, $isPublic = false)
    {
        $entities = EventPeriodic::all()->load(['category','theme'])
            ->where('is_published', 1)
        ;
        $events = $this->getMergedEntities($entities, $formated, $isPublic);
        return $events;
    }

	public function getPeriodicEventByDate( $dateString, $formated = true, $isPublic = false )
	{
        $found = $this->getAllPeriodicDates($formated, $isPublic)->first(function ($entity, $date) use($dateString)  {
            if($date === $dateString) {
                return $entity;
            }
        });
		return $found;
	}

	public function getPeriodicEventByDateAndCategory( $dateString, $categorySlug )
	{
		foreach($this->getAllPeriodicDatesByCategory($categorySlug) as $date => $entity) {
			if($date === $dateString) {
				return $entity;
			}
		}
		return null;
	}

	public function getPeriodicEventByDateAndTheme( $dateString, $themeSlug )
	{
		foreach($this->getAllPeriodicDatesByTheme($themeSlug) as $date => $entity) {
			if($date === $dateString) {
				return $entity;
			}
		}
		return null;
	}

	public function getAllPeriodicDatesByCategory( $slug )
	{
		$entities = EventPeriodic::with(['category','theme'])
			->where('is_published', 1)
			->whereHas('category', function($query) use ($slug) {
				$query->where('slug', $slug);
			})
			->get()
		;

		$events = $this->getMergedEntities($entities, true, true);
		return $events;
	}

	public function getAllPeriodicDatesByTheme( $slug )
	{
		$entities = EventPeriodic::with(['category','theme'])
			->where('is_published', 1)
			->whereHas('theme', function($query) use ($slug) {
				$query->where('slug', $slug);
			})
			->get()
		;

		$events = $this->getMergedEntities($entities, true, true);
		return $events;
	}

	public function getMergedEntities( $entities, $formated = true, $isPublic = false )
	{
		$data = [];
		if( $entities->count() ) {
			foreach ($entities as $index => $entity) {
			    $dates = $this->getPeriodicDates($entity, $formated, $isPublic);
			    if($dates && count($dates) > 0) {
                    foreach ($dates as $date) {
                        $data[$date] = $entity;
                    }
                }
			}

			$repo = new EventEntityRepository();
			$events = $repo->mapToEventEntityCollection($data);

			return $events;
		} else {
			return collect([]);
		}
	}

	public function getMergedEntitiesCached( $entities )
	{
		$events = Cache::get('mergedEntities', function () use ($entities) {
			$events = $this->getMergedEntities($entities, true, true);
			Cache::store('memcached')->put('mergedEntities', $events, $this->cacheTTL);
			return $events;
		});

		return $events;
	}
}
