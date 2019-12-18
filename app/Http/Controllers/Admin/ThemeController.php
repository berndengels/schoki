<?php
/**
 * UserController.php
 *
 * @author    Bernd Engels
 * @created   28.02.19 17:17
 * @copyright Webwerk Berlin GmbH
 */
namespace App\Http\Controllers\Admin;

use App\Http\Requests\SaveThemeRequest;
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

    public function store(SaveThemeRequest $request, $id = 0 )
    {
        $validator = Validator::make($request->post(), $request->rules(), $request->messages());

        if(!$validator->valid()) {
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }

        try {
            if($id > 0) {
                Theme::find($id)->update($request->validated());
            } else {
                Theme::create($request->validated());
            }
        } catch(Exception $e) {
            return back()->with('error','Fehler: '.$e->getMessage());
        }

        switch($request->submit) {
            case 'save':
                return back();
            case 'saveAndBack':
            default:
                return redirect()->route('admin.themeList');
        }
    }

}
