<?php
/**
 * UserController.php
 *
 * @author    Bernd Engels
 * @created   28.02.19 17:17
 * @copyright Webwerk Berlin GmbH
 */
namespace App\Http\Controllers\Admin;

use App\Forms\AddressCategoryFilterForm;
use App\Http\Requests\SaveAddressRequest;
use App\Models\Address;
use App\Forms\SearchForm;
use App\Forms\AddressForm;
use App\Models\Event;
use App\Repositories\NewsletterRepository;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Kris\LaravelFormBuilder\FormBuilder;
use Kris\LaravelFormBuilder\FormBuilderTrait;
use Newsletter;
use Throwable;

class AddressController extends MainController
{
    use FormBuilderTrait;

    static protected $model	= Address::class;
    static protected $form	= AddressForm::class;

	protected function listAll( FormBuilder $formBuilder, Request $request ) {
		$searchForm = $formBuilder->create(SearchForm::class, ['url' => route('admin.addressList')]);
		$addressCategoryForm = $formBuilder->create(AddressCategoryFilterForm::class, ['url' => route('admin.addressList')]);

		$addressCategory = $request->get('addressCategory');
		$search = $request->get('search');

		$data = Address::when($search, function($query) use ($search) {
			return $query
				->where('email','LIKE', "%${search}%");
		})->when($addressCategory, function($query) use ($addressCategory) {
			return $query
				->where('address_category_id', $addressCategory);
		})
			->sortable()
			->paginate($this->paginationLimit)
			->appends($request->except('page'))
		;


		return view('admin.addresses', [
			'searchForm'			=> $searchForm,
			'addressCategoryForm'	=> $addressCategoryForm,

			'data'      => $data,
			'addLink'   => $this->addLink,
			'entity'    => $this->entity,
			'title'     => Str::plural($this->title),
		]);
	}

	public function show( $id ) {
		$address = Address::find($id);
		return view('admin.address-show', [
			'data'      => $address,
			'listLink'  => $this->listLink,
			'entity'    => $this->entity,
			'title'     => Str::plural($this->title),
		]);
	}

	public function store( SaveAddressRequest $request, $id = 0 )
    {
        $validator = Validator::make($request->post(), $request->rules(), $request->messages());

        if(!$validator->valid()) {
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }

        $entity = ($id > 0) ? Address::find($id) : new Address();

        $values = $request->validated();
        $entity->address_category_id = $values['address_category_id'];
        $entity->email  = $values['email'];
        $entity->token	= Hash::make($entity->address_category_id.$entity->email);

        try {
            $entity->saveOrFail();
            $id = $entity->id;

			$repository = new NewsletterRepository();
			$response = $repository->addMember($entity, true);
        } catch (Exception $e) {
            return back()->with('error','Fehler: '.$e->getMessage());
        } catch (Throwable $e) {
            return back()->with('error','Fehler: '.$e->getMessage());
        }

        switch($request->submit) {
            case 'save':
                return back();
            case 'saveAndBack':
            default:
                return redirect()->route('admin.addressList');
        }
    }

	public function initialSubscribe()
	{
		$repository = new NewsletterRepository();
		$addresses	= Address::all();
		if($addresses->count()) {
			foreach($addresses as $address) {
				$repository->addMember($address, true);
				sleep(1);
			}
		}
	}
}
