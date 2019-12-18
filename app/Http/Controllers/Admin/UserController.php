<?php
/**
 * UserController.php
 *
 * @author    Bernd Engels
 * @created   28.02.19 17:17
 * @copyright Webwerk Berlin GmbH
 */
namespace App\Http\Controllers\Admin;

use App\Http\Requests\SaveUserRequest;
use App\Models\MusicStyle;
use Exception;
use Hash;
use App\Forms\UserForm;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Validation\Rule;
use Kris\LaravelFormBuilder\FormBuilder;
use Kris\LaravelFormBuilder\FormBuilderTrait;

class UserController extends MainController
{
    use FormBuilderTrait;

    static protected $model = User::class;
    static protected $form = UserForm::class;

    public function index() {

		if($this->isAdmin) {
			$data = User::sortable()->paginate( $this->paginationLimit );
		} else {
			$data = User::where('id', auth()->user()->id)->paginate( $this->paginationLimit );
		}
//		$data = User::sortable()->paginate( $this->paginationLimit );

		return view('admin.users', [
            'data'      => $data,
            'addLink'   => $this->addLink,
            'entity'    => $this->entity,
            'title'     => Str::plural($this->title),
        ]);
    }

    public function edit( FormBuilder $formBuilder, $id = 0, $options = null ) {
        $user   = ($id > 0) ? User::findOrFail($id): null;
        $form   = $formBuilder->create(UserForm::class, ['model' => $user]);

        if($id > 0) {
            $form->password->setValue(null);
        }
        return view('admin.form.user', [
            'form'      => $form,
            'listLink'  => $this->listLink,
            'title'     => $this->title,
        ]);
    }

	public function reset()
	{
		if( !$this->isAdmin ) {
			return redirect()->route('admin.userList');
		}

		$users = User::where('username','!=','bengels')->get();
//		$users = User::all();
		$count = $users->count();
		if($count) {
			/**
			 * @var $user User
			 */
			foreach($users as $user) {
				$user->password = Hash::make($user->username  . '7');
				$user->save();
			}
		}
		return redirect()->route('admin.userList', ['msg' => 'Passwörter von ' . $count . ' Usern zurückgesetzt!']);
	}

    public function store( SaveUserRequest $request, $id = 0 )
    {
        $user   = ($id > 0) ? User::find($id) : new User();
        $validator = Validator::make($request->post(), $request->rules(), $request->messages());

        if(!$validator->valid()) {
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }

        $values = $request->validated();

        $user->username = $values['username'];
        $user->email    = $values['email'];
        $user->enabled          = isset($values['enabled']) ? 1 : 0;
        $user->is_super_admin   = ($this->isAdmin && isset($values['is_super_admin'])) ? 1 : null;

        if( $values['password'] ) {
            $user->password = Hash::make($values['password']);
            die('password changed');
        }

        if(isset($values['music_style_id'])) {
            $musicStyles = collect($values['music_style_id']);

            if($musicStyles->count() && $musicStyles->first()) {
                $user->musicStyles()->sync($values['music_style_id']);
            } else {
                $user->musicStyles()->detach();
            }
        }

        try {
            $user->saveOrFail();
            $id = $user->id;
        } catch (Exception $e) {
            return back()->with('error','Fehler: '.$e->getMessage());
        }

        switch($request->submit) {
            case 'save':
                return back();
            case 'saveAndBack':
            default:
                return redirect()->route('admin.userList');
        }
    }
}
