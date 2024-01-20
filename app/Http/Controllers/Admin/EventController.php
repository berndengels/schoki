<?php
/**
 * EventsController.php
 *
 * @author    Bernd Engels
 * @created   30.01.19 18:55
 * @copyright Webwerk Berlin GmbH
 */
namespace App\Http\Controllers\Admin;

use Exception;
use App\Forms\EventTemplateSelectForm;
use App\Forms\SearchForm;
use App\Http\Requests\EventRequest;
use Illuminate\Support\Facades\Cache;
use App\Models\EventPeriodic;
use App\Models\EventTemplate;
use Illuminate\Contracts\Support\Renderable;
use App\Models\Event;
use App\Repositories\EventRepository;
use App\Repositories\EventPeriodicRepository;
use App\Forms\EventForm;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Str;
use Kris\LaravelFormBuilder\FormBuilder;
use Kris\LaravelFormBuilder\FormBuilderTrait;

class EventController extends MainController
{
    use FormBuilderTrait;

    static protected $model = Event::class;
    static protected $form	= EventForm::class;

    /**
     * Show the application dashboard.
     *
     * @return Renderable
     */
    public function listEvents(FormBuilder $formBuilder)
	{
		parent::index();
        $data = EventRepository::getEventsSinceToday()
			->paginate($this->paginationLimit)
		;
        return view('admin.events', [
            'data'      => $data,
            'addLink'   => $this->addLink,
            'entity'    => $this->entity,
            'title'     => Str::plural($this->title),
			'templateSelectForm'	=> $formBuilder->create(EventTemplateSelectForm::class, ['url'	=> route('admin.eventTemplateSelect')]),
        ]);
    }

	public function edit( FormBuilder $formBuilder, $id = 0, $options = null ) {
		parent::initReservedDates();
        return parent::edit($formBuilder, $id, [
			'dates'	=> $this->strReservedDates,
		]);
	}

	public function archive( FormBuilder $formBuilder, Request $request )
	{
		$data = EventRepository::getEventsUntilDate($this->today, $request->get('search'))
			->sortable()
			->paginate($this->paginationLimit)
			->appends($request->except('page'))
		;
		$form = $formBuilder->create(SearchForm::class, ['url'	=> route('admin.eventArchiveSearch')]);

		return view('admin.events', [
			'searchForm'	=> $form,
			'data'      	=> $data,
			'addLink'   	=> $this->addLink,
			'entity'    	=> $this->entity,
			'title'     	=> 'Archiv',
			'templateSelectForm'=> null,
		]);
	}

	public function checkForPeriodicDate( Request $request ) {
		$date		= $request->post('date');
		$repo		= new EventPeriodicRepository();
        $entity		= $repo->getPeriodicEventByDate($date, true, true);
		$response	= [
            'date'		=> $date,
			'entity'	=> ($entity) ? $entity->toArray() : null,
		];

		return response()->json($response);
	}

	public function override( FormBuilder $formBuilder, Request $request, $id = 0 )
	{
		parent::initReservedDates();
		$eventPeriodicId    = $request->post('override');
		$eventPeriodic      = EventPeriodic::find($eventPeriodicId);
		$event = $id > 0 ? Event::find($id) : new Event();

        if( $eventPeriodicId > 0 && $eventPeriodic ) {
            $attributes = [
                'is_periodic' 	=> 1,
                'title'			=> $eventPeriodic->title,
                'subtitle'		=> $eventPeriodic->subtitle,
                'description'	=> $eventPeriodic->description,
                'category'		=> $eventPeriodic->category,
                'theme'			=> $eventPeriodic->theme,
                'images'		=> $eventPeriodic->images,
                'event_time'	=> $eventPeriodic->event_time,
                'event_date'	=> $request->post('event_date'),
                'links'			=> $eventPeriodic->links->count() > 0 ? $eventPeriodic->links->join("\n") : '',
                'override'		=> $eventPeriodicId,
            ];
            $event->setRawAttributes($attributes);
        }

		$form  = $formBuilder->create(EventForm::class, ['model' => $event]);
		$formOptions    = [
			'id'        => $id,
			'form'      => $form,
			'title'     => $this->title,
			'listLink'  => $this->listLink,
			'dates' 	=> $this->strReservedDates,
		];
		return view('admin.form.'.$this->entity, $formOptions);
	}

    public function store(EventRequest $request, $id = 0)
    {
        try {
            if((int) $id > 0) {
                $event = Event::find($id);
//				dd($event);
				$event->update($request->validated());
            } else {
                $saved = Event::create($request->validated());
                $id = $saved->id;
            }
            Cache::forget($this->cacheEventKey);
        } catch(Exception $e) {
            return back()->with('error','Fehler: '.$e->getMessage());
        }
        $override = $request->post('override');
        $template = $request->post('template');

        $this->processImages($request, $id, $override, $template);

        switch($request->submit) {
            case 'save':
                return redirect()->route('admin.eventEdit', ['id' => $id]);
            case 'saveAndBack':
            default:
                return redirect()->route('admin.eventList');
        }
    }

	public function template(FormBuilder $formBuilder, Request $request ) {
			$id = $request->post('eventTemplate');

			if(!$id) {
				return null;
			}

			$template  = EventTemplate::find($id);
			if($template) {
				parent::initReservedDates();
				$event = new Event();

				$attributes = [
					'is_periodic' 	=> 0,
					'title'			=> $template->title,
					'subtitle'		=> $template->subtitle,
					'description'	=> $template->description,
					'links'			=> $template->links->count() > 0 ? $template->links->join("\n") : '',
					'category'		=> $template->category,
					'theme'			=> $template->theme,
					'images'		=> $template->images,
					'event_time'	=> $template->event_time,
					'template'		=> $id,
				];

				$event->setRawAttributes($attributes);

				$form  = $formBuilder->create(EventForm::class, ['model' => $event]);
				$data    = [
					'id'        => null,
					'form'      => $form,
					'title'     => $this->title,
					'listLink'  => $this->listLink,
					'dates' 	=> $this->strReservedDates,
				];
				return view('admin.form.'.$this->entity, $data);
			}
	}

    public function delete( $id ) {
        $model  = static::$model;
        if($model) {
            /**
             * @var $entity Model
             */
            $entity = $model::find($id);
            if($entity) {
                $entity->delete();
                Cache::forget($this->cacheEventKey);
                if(0 == $entity->is_periodic && isset($entity->images)) {
                    $this->removeImages($entity->images);
                }
                return redirect()->route('admin.'.$this->entity.'List');
            }
        }
        return null;
    }

}
