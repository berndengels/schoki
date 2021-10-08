<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Route;
use Carbon\Carbon;
use App\Models\Event;
use App\Helper\MyDate;
use Laravelium\Feed\Feed;
use Illuminate\Support\Str;
use App\Entities\EventEntity;
use Illuminate\Http\Response;
use Eluceo\iCal\Property\Event\Geo;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use App\Repositories\EventEntityRepository;
use App\Repositories\EventPeriodicRepository;
use App\Http\Controllers\Controller as BaseController;
use Eluceo\iCal\Component\Calendar as iCal;
use Eluceo\iCal\Component\Event as iCalEvent;

class EventController extends BaseController
{
	/**
	 * @var Collection
	 */
	protected $actualEvents;
	/**
	 * @var Collection
	 */
	protected $actualEventsByCategory;
	/**
	 * @var Collection
	 */
	protected $actualEventsByTheme;

	public function __construct()
	{
	    if(app()->environment('dev')) {
            $this->actualEvents = Event::allActualMerged();
        } else {
            if (!Cache::has($this->cacheEventKey)) {
                Cache::put($this->cacheEventKey, Event::allActualMerged(), config('cache.ttl'));
            }
            $this->actualEvents = Cache::get($this->cacheEventKey, collect([]));
        }
	}

	public function show($date)
	{
		/**
		 * @var $event EventEntity
		 */
		$event = $this->actualEvents->get($date);
		$dateObj = Carbon::createFromFormat('Y-m-d', $date);
		$expired = Carbon::today() > $dateObj;
		return view('public.events-show', [
			'expired'	=> $expired,
			'event' 	=> $event,
		]);
	}

	public function feed()
	{
		/**
		 * @var $feed Feed
		 */
		$feed = App::make('feed');
//		$feed->setCache(180, 'laravelFeed');
		$feed->setCustomView('public.templates.rss');

//		if (!$feed->isCached()) {
			$events = $this->actualEvents;
			// set your feed's title, description, link, pubdate and language
			$feed->title = 'Berlin-Mitte Schokoladen Events';
			$feed->description = 'Die aktuellen Schokoladen Events';
			$feed->logo = config('app.url') . '/img/batcow_yellow.png';
			$feed->link = url('public.events');
//			$feed->setDateFormat('carbon'); // 'datetime', 'timestamp' or 'carbon'
			$feed->pubdate = $events->first()->getCreatedAt()->toRfc822String();
			$feed->lang = 'de';
			$feed->setShortening(true); // true or false
			$feed->setTextLimit(255); // maximum length of description text

			foreach ($events as $event) {
				// set item's title, author, url, pubdate, description, content, enclosure (optional)*
				$feed->addItem($event->toFeedArray());
			}
//		}

		header('Content-Type: application/rss+xml; charset=UTF-8', true);
		return $feed->render('rss');
	}

	/**
	 * @return Collection
	 */
	public function getActualEventsByDate($date)
	{
		return $this->actualEvents->get($date);
	}

	public function showByDate( $date )
	{
		return $this->actualEvents->get($date);
	}

/*
	public function getEvents()
	{
		$data = Event::with(['category','theme'])->paginate(config('event.eventsPaginationLimit'));
		return view('public.events', ['data' => $data ]);
	}

	public function getActualEvents()
	{
		$data = Event::allActual()->paginate(config('event.eventsPaginationLimit'));
		return view('public.events', ['data' => $data ]);
	}
*/
	public function getActualMergedEvents()
	{
		return view('public.events-lazy', [
//			'data' => $this->actualEvents->paginate(config('event.eventsPaginationLimit')),
			'data'	=> $this->actualEvents,
			'today' => MyDate::getUntilValidDate(),
//			'route'	=> '/events/lazy',
		]);
	}

	public function getActualMergedEventsByCategory()
	{
		$routeArr   = explode('.', Route::currentRouteName()) ;
		$slug       = array_pop($routeArr);
		$cacheKey   = $this->cacheEventCategoryKey . ucfirst( Str::camel($slug));

        if(!Cache::has($cacheKey)) {
            $repo 		= new EventPeriodicRepository();
            $repoEntity	= new EventEntityRepository();

            $periodicEvents	= $repo->getAllPeriodicDatesByCategory($slug);
            $datedEvents	= Event::byCategorySlug($slug)->get()->keyBy('event_date');

            $mappedEvents = $repoEntity->mapToEventEntityCollection($datedEvents);
//		$data = $periodicEvents->merge($mappedEvents)->sortKeys()->paginate(config('event.eventsPaginationLimit'));
            $this->actualEventsByCategory = $periodicEvents->merge($mappedEvents)->sortKeys();
            Cache::put($cacheKey, $this->actualEventsByCategory, config('cache.ttl'));
        } else {
            $this->actualEventsByCategory = Cache::get($cacheKey, collect([]));
        }

		return view('public.events-lazy', [
//			'data' => $this->actualEvents->paginate(config('event.eventsPaginationLimit')),
			'data'	=> $this->actualEventsByCategory,
			'today' => MyDate::getUntilValidDate(),
			'route'	=> '/events/lazyByCategory/'.$slug,
		]);
	}

	public function getActualMergedEventsByTheme()
	{
		$routeArr   = explode('.', Route::currentRouteName()) ;
		$slug       = array_pop($routeArr);
        $cacheKey   = $this->cacheEventThemeKey . ucfirst( Str::camel($slug));

        if(!Cache::has($cacheKey)) {
            $repo 		= new EventPeriodicRepository();
            $repoEntity	= new EventEntityRepository();

            $periodicEvents	= $repo->getAllPeriodicDatesByTheme($slug);
            $datedEvents	= Event::byThemeSlug($slug)->get()->keyBy('event_date');
            $mappedEvents = $repoEntity->mapToEventEntityCollection($datedEvents);
//		$data = $periodicEvents->merge($mappedEvents)->sortKeys()->paginate(config('event.eventsPaginationLimit'));
            $this->actualEventsByTheme = $periodicEvents->merge($mappedEvents)->sortKeys();
            Cache::put($cacheKey, $this->actualEventsByTheme, config('cache.ttl'));
        } else {
            $this->actualEventsByTheme = Cache::get($cacheKey, collect([]));
        }

//		return view('public.events-lazy', ['theme' => $slug, 'data' => $data ]);
		return view('public.events-lazy', [
//			'data' => $this->actualEvents->paginate(config('event.eventsPaginationLimit')),
			'data'	=> $this->actualEventsByTheme,
			'today' => MyDate::getUntilValidDate(),
			'route'	=> '/events/lazyByTheme/'.$slug,
		]);
	}

    /*
	public function calendar($year, $month)
	{
        dd($year, $month);
		$dates = [];
		$result = ['error' => true];

		foreach($this->actualEvents as $date => $event) {
			list($y,$m,) = explode('-', $date);
			if($year == $y && $month == $m) {
				$dates[] = $event->toCalendarData();
			}
		}
		if( count($dates) > 0 ) {
			$result = $dates;
		}

		return json_encode($result);
	}
*/

    public function calendar($year, $month)
    {
        $dates = [];
        $result = ['error' => true];

        /**
         * @var $event EventEntity
         */
        foreach($this->actualEvents as $date => $event) {
            list($y,$m,) = explode('-', $date);
//            if($year == $y && $month == $m) {
                $dates[] = $event->toCalendarData();
//            }
        }
        if( count($dates) > 0 ) {
            $result = $dates;
        }

        return json_encode($result);
    }

    public function lazy($date)
	{
		/**
		 * @var $event EventEntity
		 */
		$event = $this->actualEvents->get($date);
		return view('public.templates.event', ['event' => $event ]);
	}
/*
	public function lazyByCategory($category , $date)
	{
		$event = Event::MergedByDateAndCategory( $date, $category );
		return view('public.templates.event', ['event' => $event ]);
	}

	public function lazyByTheme($theme , $date)
	{
		$event = $this->actualEventsByTheme->get($date);
		return view('public.templates.event', ['event' => $event ]);
	}
*/
	public function ical() {
		$iCal = new iCal(config('app.url'));
		$iCal
			->setName(env('ICAL_NAME'))
			->setDescription(env('ICAL_DESCRIPTION'))
			->setTimezone('Europe/Berlin')
//			->setMethod('PUBLISH')
		;
		/**
		 * @var $evt EventEntity
		 */
		foreach($this->actualEvents as $evt) {
			$iCalEvent	= new iCalEvent();
			$iCalEvent
				->setUseTimezone(true)
				->setTimezoneString(config('app.timezone'))
				->setLocation(env('ICAL_LOCATION'), env('APP_NAME'), new Geo(env('LOCATION_LAT'), env('LOCATION_LNG')))
				->setCategories([$evt->getCategory()])
				->setDtStart($evt->getEventDateTime())
				->setSummary($evt->getTitle())
				->setDescription($evt->getDescriptionText())
				->setDescriptionHTML($evt->getDescriptionSanitized())
			;
			$iCal->addComponent($iCalEvent);
		}
		$response = new Response();
		$response->setContent($iCal->render())->setCharset('UTF-8')->setStatusCode(Response::HTTP_OK);
		$response->headers->set('Content-Type', 'text/calendar; charset=utf-8');
		$response->headers->set('Content-Disposition', 'attachment; filename="cal.ics"');

		return $response;
	}
}
