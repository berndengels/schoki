<?php
/**
 * UserController.php
 *
 * @author    Bernd Engels
 * @created   28.02.19 17:17
 * @copyright Webwerk Berlin GmbH
 */
namespace App\Http\Controllers\Admin;

use App\Http\Requests\ThemeRequest;
use App\Models\Theme;
use App\Forms\ThemeForm;
use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Kris\LaravelFormBuilder\FormBuilder;
use Kris\LaravelFormBuilder\FormBuilderTrait;

class ThemeController extends MainController
{
    use FormBuilderTrait;

    static protected $model = Theme::class;
    static protected $form = ThemeForm::class;

    public function store(ThemeRequest $request, $id = 0 )
    {
        try {
            if($id > 0) {
                Theme::find($id)->update($request->validated());
            } else {
                $saved = Theme::create($request->validated());
                $id = $saved->id;
            }
        } catch(Exception $e) {
            return back()->with('error','Fehler: '.$e->getMessage());
        }

        switch($request->submit) {
            case 'save':
                return redirect()->route('admin.themeEdit', ['id' => $id]);
            case 'saveAndBack':
            default:
                return redirect()->route('admin.themeList');
        }
    }

}
