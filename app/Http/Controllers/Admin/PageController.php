<?php
/**
 * UserController.php
 *
 * @author    Bernd Engels
 * @created   28.02.19 17:17
 * @copyright Webwerk Berlin GmbH
 */
namespace App\Http\Controllers\Admin;

use App\Http\Requests\SavePageRequest;
use App\Models\Page;
use App\Forms\PageForm;
use Exception;
use Illuminate\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Kris\LaravelFormBuilder\FormBuilder;
use Kris\LaravelFormBuilder\FormBuilderTrait;

class PageController extends MainController
{
    use FormBuilderTrait;

    static protected $model = Page::class;
    static protected $form = PageForm::class;

    public function store( SavePageRequest $request, $id = 0 )
    {
        $entity = ($id > 0) ? Page::find($id) : new Page();
        $values = $request->validated();

        $entity->title          = $values['title'];
        $entity->body           = $values['body'];
        $entity->is_published   = isset($values['is_published']) ? 1 : 0;

        try {
            $entity->saveOrFail();
            $id = $entity->id;
        } catch (Exception $e) {
            return back()->with('error','Fehler: '.$e->getMessage());
        }

        switch($request->submit) {
            case 'save':
                return redirect()->route('admin.pageEdit', ['id' => $id]);
            case 'saveAndBack':
            default:
                return redirect()->route('admin.pageList');
        }
    }
}
