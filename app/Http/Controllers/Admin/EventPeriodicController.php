<?php
/**
 * EventsController.php
 *
 * @author    Bernd Engels
 * @created   30.01.19 18:55
 * @copyright Webwerk Berlin GmbH
 */
namespace App\Http\Controllers\Admin;

use App\Models\PeriodicPosition;
use App\Models\PeriodicWeekday;
use Auth;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Carbon\CarbonInterval;
use App\Libs\EventDateTime;
use App\Models\Theme;
use App\Models\Category;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use App\Models\Event;
use App\Models\Images;
use Illuminate\Http\Request;
use App\Models\EventPeriodic;
use App\Forms\EventPeriodicForm;
use App\Http\Requests\UploadRequest;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Kris\LaravelFormBuilder\Form;
use Kris\LaravelFormBuilder\FormBuilder;
use App\Http\Controllers\Admin\MainController;
use Kris\LaravelFormBuilder\FormBuilderTrait;
use App\Http\Requests\SaveEventPediodicRequest;

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

        if(!$form->isValid()) {
            die('error');
        }
		$dates = '';

		if($id > 0) {
			$eventDateTime = new EventDateTime();
			$dates = $eventDateTime->getPeriodicEventDates($event->periodicPosition->search_key, $event->periodicWeekday->name_en);
			array_walk($dates, function(&$v){
				$v = "'$v'";
			});
			$dates = implode(',', $dates);
		}

        return view('admin.form.eventPeriodic', [
            'id'        => $id,
            'form'      => $form,
            'dates'     => $dates,
            'title'     => $this->title,
            'listLink'  => $this->listLink,
        ]);
    }

    public function store( SaveEventPediodicRequest $request, $id = 0)
    {
        $form = $this->form(EventPeriodicForm::class);
        if (!$form->isValid()) {
            return redirect()->back()->withErrors($form->getErrors())->withInput();
        }
        $periodicDate = $request->post('periodicDate');
        $exist = EventPeriodic::wherePeriodicPositionId($periodicDate['periodic_position_id'])
            ->wherePeriodicWeekdayId($periodicDate['periodic_weekday_id'])
            ->first()
        ;
        if($exist && $id === 0) {
            return redirect()->back()->with('error', 'Für dieses zyklischde Datum existiert bereits periodisches Event ("'.$exist->title.'")!');
        }

        $validated = array_merge($request->validated(), $periodicDate);

        try {
            if($id > 0) {
                EventPeriodic::find($id)->update($validated);
            } else {
                $saved = EventPeriodic::create($validated);
                $id = $saved->id;
            }
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
