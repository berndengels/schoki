<?php
/**
 * EventsController.php
 *
 * @author    Bernd Engels
 * @created   30.01.19 18:55
 * @copyright Webwerk Berlin GmbH
 */
namespace App\Http\Controllers\Admin;

use App\Http\Requests\EventTemplateRequest;
use Exception;
use App\Models\EventTemplate;
use App\Forms\EventTemplateForm;
use Kris\LaravelFormBuilder\FormBuilderTrait;

class EventTemplateController extends MainController
{
    use FormBuilderTrait;

    static protected $model = EventTemplate::class;
    static protected $form	= EventTemplateForm::class;

    public function store(EventTemplateRequest $request, $id = 0 )
    {
        try {
            if($id > 0) {
                EventTemplate::find($id)->update($request->validated());
            } else {
                $saved = EventTemplate::create($request->validated());
                $id = $saved->id;
            }
        } catch(Exception $e) {
            return back()->with('error','Fehler: '.$e->getMessage());
        }

        $this->processImages($request, $id);

        switch($request->submit) {
            case 'save':
                return redirect()->route('admin.eventTemplateEdit', ['id' => $id]);
            case 'saveAndBack':
            default:
                return redirect()->route('admin.eventTemplateList');
        }
    }
}
