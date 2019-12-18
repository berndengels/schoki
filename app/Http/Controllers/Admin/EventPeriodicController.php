<?php
/**
 * EventsController.php
 *
 * @author    Bernd Engels
 * @created   30.01.19 18:55
 * @copyright Webwerk Berlin GmbH
 */
namespace App\Http\Controllers\Admin;

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
        $validator = Validator::make($request->post(), $request->rules(), $request->messages());

        if(!$validator->valid()) {
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }

        try {
            if($id > 0) {
                EventPeriodic::find($id)->update($request->validated());
            } else {
                EventPeriodic::create($request->validated());
            }
        } catch(Exception $e) {
            return back()->with('error','Fehler: '.$e->getMessage());
        }

        $this->processImages($request, $id);

        switch($request->submit) {
            case 'save':
                return back();
            case 'saveAndBack':
            default:
                return redirect()->route('admin.eventPeriodicList');
        }
    }

}
