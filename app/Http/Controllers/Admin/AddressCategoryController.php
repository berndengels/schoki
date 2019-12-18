<?php
/**
 * AddressCategory.php
 *
 * @author    Bernd Engels
 * @created   15.06.19 19:19
 * @copyright Webwerk Berlin GmbH
 */

namespace App\Http\Controllers\Admin;

use App\Entities\Newsletter\ContactEntity;
use App\Http\Requests\SaveAddressCategoryRequest;
use Exception;
use Illuminate\Http\Request;
use App\Models\AddressCategory;
use App\Forms\AddressCategoryForm;
use App\Repositories\NewsletterRepository;
use Illuminate\Support\Facades\Validator;
use Kris\LaravelFormBuilder\FormBuilder;
use Kris\LaravelFormBuilder\FormBuilderTrait;
use Throwable;

class AddressCategoryController extends MainController {

	use FormBuilderTrait;

	static protected $model	= AddressCategory::class;
	static protected $form	= AddressCategoryForm::class;

	public function store( SaveAddressCategoryRequest $request, $id = 0 )
	{
        $validator = Validator::make($request->post(), $request->rules(), $request->messages());

        if(!$validator->valid()) {
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }

		$entity = ($id > 0) ? AddressCategory::find($id) : new AddressCategory();

        $values = $request->validated();
        $entity->name = $values['name'];

		try {
			$response = null;
			if(!$entity->tag_id) {
                $repository = new NewsletterRepository();
				$listTag = $repository->getListTagByName($entity->name);
				if(!$listTag) {
					$response = $repository->createListTag($entity->name);
					$entity->tag_id = $response['id'];
				} else {
					$entity->tag_id = $listTag['id'];
				}
			}
            try {
                $entity->saveOrFail();
            } catch (Throwable $e) {
                return back()->with('error','Fehler: '.$e->getMessage());
            }
            $id = $entity->id;
		} catch (Exception $e) {
            return back()->with('error','Fehler: '.$e->getMessage());
		}

		switch($request->submit) {
			case 'save':
                return back();
			case 'saveAndBack':
			default:
				return redirect()->route('admin.addressCategoryList');
		}
	}
}
