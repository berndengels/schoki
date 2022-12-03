<?php
/**
 * EventEntityRepository.php
 *
 * @author    Bernd Engels
 * @created   16.04.19 16:13
 * @copyright Webwerk Berlin GmbH
 */

namespace App\Repositories;

use Carbon\Carbon;
use App\Entities\EventEntity;
use App\Models\EventPeriodic;

class EventEntityRepository {

	public function mapToEventEntity( $event, $date )
	{
		if(!$event) {
			return null;
		}

		$attributes = $event->getAttributes();
		unset($attributes['event_date']);
		$entity 	= new EventEntity($attributes);
        $dateObj	= new Carbon($date, 'Europe/Berlin');

		$entity
			->setEventDate($dateObj)
			->setDomId('e' . $dateObj->format('Ymd'))
			->setDescriptionSanitized($event->descriptionSanitized)
			->setImages($event->images)
			->setIsPeriodic($event instanceof EventPeriodic ? 1 : 0)
			->setCategory($event->category ? $event->category : null)
			->setTheme($event->theme ? $event->theme : null)
			->setCreatedBy($event->createdBy)
			->setUpdatedBy($event->updatedBy ? $event->updatedBy : $event->createdBy)
		;

		return $entity;
	}

	public function mapToEventEntityCollection( $data )
	{
		$events = [];
		if( count($data) > 0 ) {
			foreach($data as $date => $item) {
				$attributes = $item->getAttributes();
				unset($attributes['event_date']);
				$event 		= new EventEntity($attributes);
                $dateObj	= new Carbon($date, 'Europe/Berlin');

				$event
					->setEventDate($dateObj)
					->setDomId('e' . $dateObj->format('Ymd'))
					->setDescriptionSanitized($item->descriptionSanitized)
					->setImages($item->images)
					->setIsPeriodic($item instanceof EventPeriodic ? 1 : 0)
					->setCategory($item->category ? $item->category : null)
					->setTheme($item->theme ? $item->theme : null)
					->setCreatedBy($item->createdBy)
					->setUpdatedBy($item->updatedBy ? $item->updatedBy : $item->createdBy)
				;

				$events[$date] = $event;
			}
			return collect($events);
		}

		return collect([]);
	}
}
