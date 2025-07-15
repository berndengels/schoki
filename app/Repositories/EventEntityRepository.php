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
//            ->setEventDate($dateObj)
			->setEventDate($date)
			->setTitle($event->title)
			->setDomId('e' . $dateObj->format('Ymd'))
			->setDescriptionSanitized($event->descriptionSanitized)
            ->setDescriptionWithoutVideo($event->descriptionWithoutVideo)
			->setImages($event->images)
			->setIsPeriodic($event instanceof EventPeriodic ? 1 : 0)
			->setCategory($event->category ? $event->category : null)
			->setTheme($event->theme ? $event->theme : null)
			->setDj($event->dj)
			->setPromoter($event->promoter)
//			->setCreatedBy($event->createdBy)
//			->setUpdatedBy($event->updatedBy ? $event->updatedBy : $event->createdBy)
		;
        if(auth()->check()) {
            $entity
                ->setCreatedBy($event->createdBy)
                ->setUpdatedBy($event->updatedBy ? $event->updatedBy : $event->createdBy)
            ;
        }

		return $entity;
	}

	public function mapToEventEntityCollection( $data )
	{
		$events = [];
        $isAuth = auth()->check();

		if( count($data) > 0 ) {
			foreach($data as $date => $item) {
				$attributes = $item->getAttributes();
				unset($attributes['event_date']);
				$event 		= new EventEntity($attributes);
                $dateObj	= new Carbon($date, 'Europe/Berlin');

				$event
//					->setEventDate($dateObj)
					->setTitle($item->title)
                    ->setEventDate($date)
					->setDomId('e' . $dateObj->format('Ymd'))
                    ->setDescriptionSanitized($item->descriptionSanitized)
                    ->setDescriptionWithoutVideo($item->descriptionWithoutVideo)
					->setImages($item->images)
					->setIsPeriodic($item instanceof EventPeriodic ? 1 : 0)
					->setCategory($item->category ? $item->category : null)
					->setTheme($item->theme ? $item->theme : null)
					->setDj($item->dj)
					->setPromoter($item->promoter)
//					->setCreatedBy($item->createdBy)
//					->setUpdatedBy($item->updatedBy ? $item->updatedBy : $item->createdBy)
				;
                if($isAuth) {
                    $event
                        ->setCreatedBy($item->createdBy)
                        ->setUpdatedBy($item->updatedBy ? $item->updatedBy : $item->createdBy)
                    ;
                }

				$events[$date] = $event;
			}
			return collect($events);
		}

		return collect([]);
	}
}
