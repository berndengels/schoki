<?php

namespace App\Http\Controllers;

use Eluceo\iCal\Property\Event\Geo;
use Illuminate\Http\Response;
use Carbon\Carbon;
use App\Models\Event;
use App\Helper\MyDate;
use App\Entities\EventEntity;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\App;
use App\Repositories\EventEntityRepository;
use App\Repositories\EventPeriodicRepository;
use Illuminate\Routing\Controller as BaseController;
use Laravelium\Feed\Feed;
use Eluceo\iCal\Component\Calendar as iCal;
use Eluceo\iCal\Component\Event as iCalEvent;
use Route;

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
        $this->actualEvents = Event::allActualMerged();
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
		$routeArr = explode('.', Route::currentRouteName()) ;
		$slug = array_pop($routeArr);

		$repo 		= new EventPeriodicRepository();
		$repoEntity	= new EventEntityRepository();

		$periodicEvents	= $repo->getAllPeriodicDatesByCategory($slug);
		$datedEvents	= Event::byCategorySlug($slug)->get()->keyBy('event_date');

		$mappedEvents = $repoEntity->mapToEventEntityCollection($datedEvents);
//		$data = $periodicEvents->merge($mappedEvents)->sortKeys()->paginate(config('event.eventsPaginationLimit'));
		$this->actualEventsByCategory = $periodicEvents->merge($mappedEvents)->sortKeys();
		return view('public.events-lazy', [
//			'data' => $this->actualEvents->paginate(config('event.eventsPaginationLimit')),
			'data'	=> $this->actualEventsByCategory,
			'today' => MyDate::getUntilValidDate(),
			'route'	=> '/events/lazyByCategory/'.$slug,
		]);
	}

	public function getActualMergedEventsByTheme()
	{
		$routeArr = explode('.', Route::currentRouteName()) ;
		$slug = array_pop($routeArr);

		$repo 		= new EventPeriodicRepository();
		$repoEntity	= new EventEntityRepository();

		$periodicEvents	= $repo->getAllPeriodicDatesByTheme($slug);
		$datedEvents	= Event::byThemeSlug($slug)->get()->keyBy('event_date');
		$mappedEvents = $repoEntity->mapToEventEntityCollection($datedEvents);
//		$data = $periodicEvents->merge($mappedEvents)->sortKeys()->paginate(config('event.eventsPaginationLimit'));
		$this->actualEventsByTheme = $periodicEvents->merge($mappedEvents)->sortKeys();

//		return view('public.events-lazy', ['theme' => $slug, 'data' => $data ]);
		return view('public.events-lazy', [
//			'data' => $this->actualEvents->paginate(config('event.eventsPaginationLimit')),
			'data'	=> $this->actualEventsByTheme,
			'today' => MyDate::getUntilValidDate(),
			'route'	=> '/events/lazyByTheme/'.$slug,
		]);
	}

	public function calendar()
	{
		$selectedYear	= request()->get('year');
		$selectedMonth	= request()->get('month');
		$dates = [];
		$result = ['error' => true];

		/**
		 * @var $event EventEntity
		 */
		foreach($this->actualEvents as $date => $event) {
			list($year,$month,) = explode('-', $date);
			if($selectedYear == $year && $selectedMonth == $month) {
				$dates[] = $event->toCalendarData();
			}
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

	public function lazyByCategory($category , $date)
	{
//		$category = Category::where('slug', $category);
		/**
		 * @var $event EventEntity
		 */
		$event = Event::MergedByDateAndCategory( $date, $category );
		return view('public.templates.event', ['event' => $event ]);
	}

	public function lazyByTheme($theme , $date)
	{
		/**
		 * @var $event EventEntity
		 */
		$event = $this->actualEventsByTheme->get($date);
		return view('public.templates.event', ['event' => $event ]);
	}

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
