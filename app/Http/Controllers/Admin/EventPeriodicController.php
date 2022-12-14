<?php
/**
 * EventsController.php
 *
 * @author    Bernd Engels
 * @created   30.01.19 18:55
 * @copyright Webwerk Berlin GmbH
 */
namespace App\Http\Controllers\Admin;

use App\Repositories\EventPeriodicRepository;
use Cache;
use Exception;
use App\Libs\EventDateTime;
use App\Models\EventPeriodic;
use App\Forms\EventPeriodicForm;
use Kris\LaravelFormBuilder\FormBuilder;
use Kris\LaravelFormBuilder\FormBuilderTrait;
use App\Http\Requests\EventPediodicRequest;
use Illuminate\Contracts\Support\Renderable;

class EventPeriodicController extends MainController
{
    use FormBuilderTrait;

    static protected $model = EventPeriodic::class;
    static protected $form = EventPeriodicForm::class;

    /**
     * Show the application dashboard.
     *
     * @return Renderable
     */

    public function edit( FormBuilder $formBuilder, $id = 0, $options = null ) {
        $event  = ($id > 0) ? EventPeriodic::findOrFail($id): null;
        $form   = $formBuilder->create(EventPeriodicForm::class, ['model' => $event]);
        $repo   = new EventPeriodicRepository();
		$dates = $repo->getAllPeriodicDates()->keys()->map(fn($item) => "'".$item."'")->implode(',');

        if($id > 0) {
			$eventDateTime = new EventDateTime();
			$dates = collect($eventDateTime
                ->getPeriodicEventDates($event->periodic_position, $event->periodic_weekday))
                ->map(fn($item) => "'".$item."'")
                ->toArray()
            ;

			if($dates && count($dates) > 0) {
                $dates = implode(',', $dates);
            }
		}

        return view('admin.form.eventPeriodic', [
            'id'        => $id,
            'form'      => $form,
            'dates'     => $dates,
            'title'     => $this->title,
            'listLink'  => $this->listLink,
        ]);
    }

    public function store(EventPediodicRequest $request, $id = 0)
    {
        $form = $this->form(EventPeriodicForm::class);
        if (!$form->isValid()) {
            return redirect()->back()->withErrors($form->getErrors())->withInput();
        }
        $exist = EventPeriodic::wherePeriodicPosition($request['periodic_position'])
            ->wherePeriodicWeekday($request['periodic_weekday'])
            ->first()
        ;
        if($exist && $id === 0) {
            return redirect()->back()->with('error', 'Für dieses zyklischde Datum existiert bereits periodisches Event ("'.$exist->title.'")!');
        }

        try {
            if($id > 0) {
                EventPeriodic::find($id)->update($request->validated());
            } else {
                $saved = EventPeriodic::create($request->validated());
                $id = $saved->id;
            }
            Cache::flush();
        } catch(Exception $e) {
            return back()->with('error','Fehler: '.$e->getMessage());
        }

        $this->processImages($request, $id);

        switch($request->submit) {
            case 'save':
                return redirect()->route('admin.eventPeriodicEdit', ['id' => $id]);
            case 'saveAndBack':
            default:
                return redirect()->route('admin.eventPeriodicList');
        }
    }
}
