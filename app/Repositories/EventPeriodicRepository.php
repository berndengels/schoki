<?php

namespace App\Repositories;

use App\Libs\EventDateTime;
use App\Models\EventPeriodic;

class EventPeriodicRepository extends MainEventRepository {

    protected $eventDateTime = null;

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
			foreach ($entities as $entity) {
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
}
