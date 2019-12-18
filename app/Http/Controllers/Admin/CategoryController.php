<?php
/**
 * UserController.php
 *
 * @author    Bernd Engels
 * @created   28.02.19 17:17
 * @copyright Webwerk Berlin GmbH
 */
namespace App\Http\Controllers\Admin;

use App\Http\Requests\SaveCategoryRequest;
use App\Models\Category;
use App\Forms\CategoryForm;
use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Kris\LaravelFormBuilder\FormBuilderTrait;

class CategoryController extends MainController
{
    use FormBuilderTrait;

    static protected $model	= Category::class;
    static protected $form	= CategoryForm::class;

    public function store(SaveCategoryRequest $request, $id = 0 )
    {
        $validator = Validator::make($request->post(), $request->rules(), $request->messages());

        if(!$validator->valid()) {
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }

        try {
            if($id > 0) {
                Category::find($id)->update($request->validated());
            } else {
                Category::create($request->validated());
            }
        } catch(Exception $e) {
            return back()->with('error','Fehler: '.$e->getMessage());
        }

        switch($request->submit) {
            case 'save':
                return back();
            case 'saveAndBack':
            default:
                return redirect()->route('admin.categoryList');
        }
    }
}
