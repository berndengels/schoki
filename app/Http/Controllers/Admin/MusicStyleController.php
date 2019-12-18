<?php
/**
 * UserController.php
 *
 * @author    Bernd Engels
 * @created   28.02.19 17:17
 * @copyright Webwerk Berlin GmbH
 */
namespace App\Http\Controllers\Admin;

use App\Http\Requests\SaveMusicStyleRequest;
use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\MusicStyle;
use App\Forms\MusicStyleForm;
use Illuminate\Support\Facades\DB;
use Kris\LaravelFormBuilder\FormBuilder;
use Kris\LaravelFormBuilder\FormBuilderTrait;

class MusicStyleController extends MainController
{
    use FormBuilderTrait;

    static protected $model = MusicStyle::class;
    static protected $form = MusicStyleForm::class;
	static protected $orderBy = 'name';

	public function store( SaveMusicStyleRequest $request, $id = 0 )
    {
        $validator = Validator::make($request->post(), $request->rules(), $request->messages());

        if(!$validator->valid()) {
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }

        $entity = ($id > 0) ? MusicStyle::find($id) : new MusicStyle();

        $values = $request->validated();
        $entity->name = $values['name'];

        try {
            $entity->saveOrFail();
            $id = $entity->id;
        } catch (Exception $e) {
            return back()->with('error','Fehler: '.$e->getMessage());
        }

        switch($request->submit) {
            case 'save':
                return back();
            case 'saveAndBack':
            default:
                return redirect()->route('admin.musicStyleList');
        }
    }
}
