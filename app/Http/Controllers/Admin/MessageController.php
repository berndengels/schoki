<?php
/**
 * UserController.php
 *
 * @author    Bernd Engels
 * @created   28.02.19 17:17
 * @copyright Webwerk Berlin GmbH
 */
namespace App\Http\Controllers\Admin;

use App\Forms\BandsForm;
use App\Forms\Filter\MessageFilterForm;
use App\Models\Message;
use Exception;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Kris\LaravelFormBuilder\FormBuilder;
use Kris\LaravelFormBuilder\FormBuilderTrait;

class MessageController extends MainController
{
    use FormBuilderTrait;

    static protected $model	= Message::class;
    static protected $form	= BandsForm::class;

	protected function getList( FormBuilder $formBuilder, Request $request )
	{
		$musicStyle = $request->get('musicStyle');
		$model = static::$model;
		$data = $model::with('musicStyle')->sortable(['created_at' => 'desc'])
			->when($musicStyle, function($query) use($musicStyle) {
				return $query->where('music_style_id', $musicStyle);
			})
			->paginate( $this->paginationLimit )
		;
		$filter = $formBuilder->create(MessageFilterForm::class, [], [
			'musicStyle'=> $musicStyle,
		]);
		$data = [
			'data'      => $data,
			'filter'	=> $filter,
			'addLink'   => $this->addLink,
			'entity'    => $this->entity,
			'title'     => Str::plural($this->title),
		];
		$view = view('admin.messages', $data);
		return $view;
	}

	public function show( $id ) {
		$message = Message::find($id);

		return view('admin.messageShow', [
			'data'      => $message,
			'listLink'  => $this->listLink,
			'entity'    => $this->entity,
			'title'     => Str::plural($this->title),
		]);
	}

	public function store( Request $request )
    {
        $form   = $this->form(BandsForm::class);
        $entity = new Message();

        if (!$form->isValid()) {
            return redirect()->back()->withErrors($form->getErrors())->withInput();
        }
        $values = $request->post();
        $entity->name   = $values['name'];
        $entity->email  = $values['email'];
        $entity->msg    = $values['msg'];

        try {
            $entity->saveOrFail();
            $id = $entity->id;
        } catch (Exception $e) {
            return back()->with('error','Fehler: '.$e->getMessage());
        }

        switch($request->submit) {
            case 'save':
                return redirect()->route('admin.messageEdit', ['id' => $id]);
            case 'saveAndBack':
            default:
                return redirect()->route('admin.messageList');
        }
    }
}
