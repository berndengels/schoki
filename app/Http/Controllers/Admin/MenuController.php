<?php
/**
 * UserController.php
 *
 * @author    Bernd Engels
 * @created   28.02.19 17:17
 * @copyright Webwerk Berlin GmbH
 */
namespace App\Http\Controllers\Admin;

use App\Libs\Icons;
use Exception;
use Illuminate\Support\Facades\Cache;
use Request;
use Response;
use Carbon\Carbon;
use App\Models\Menu;
use App\Forms\MenuForm;
use App\Repositories\MenuRepository;
use App\Repositories\Tree;
use Illuminate\Support\Str;
use Kalnoy\Nestedset\NestedSet;
use App\Http\Controllers\Controller;
use Kris\LaravelFormBuilder\FormBuilder;
use Kris\LaravelFormBuilder\FormBuilderTrait;
use Illuminate\Database\Eloquent\Collection;

class MenuController extends Controller
{
    use FormBuilderTrait;

	/**
	 * @var Menu
	 */
    static protected $model = Menu::class;
	/**
	 * @var MenuForm
	 */
    static protected $form = MenuForm::class;
	/**
	 * @var MenuRepository
	 */
	protected $repo;
	/**
	 * @var Tree
	 */
	protected $tree;
	/**
	 * @var Menu
	 */
	protected $root;
	protected $entity       = '';
	protected $title        = '';
	protected $entityPlural = '';

	public function __construct(){
		parent::__construct();
		$this->middleware('auth');

		$this->repo = new MenuRepository();
		$this->tree = new Tree();
		$this->root = Menu::find(1);
		$this->today = Carbon::today('Europe/Berlin');
		$model = self::$model;
		if( $model ) {
			$this->entity   = lcfirst(class_basename($model));
			$this->entityPlural = Str::plural($this->entity);
			$this->title    = ucfirst($this->entity);
		}
	}

	public function show( FormBuilder $formBuilder )
    {
		try {
			$form  = $formBuilder->create(MenuForm::class);
			$options    = [
				'form'      => $form,
				'title'     => $this->title,
			];
			return view('admin.menus', $options);
		} catch(Exception $e) {
			die($e->getMessage());
		}
    }

	public function icons()
	{
		$icons = Icons::getCachedList();

		if($icons->count()) {
			$response = ['icons' => $icons];
			return response()->json($response);
		}

		return null;
	}

	public function operation( $operation )
	{
		/**
		 * @var Menu $parent
		 * @var NestedSet $node
		 */
		$request		= request();
		$id				= ('#' === $request->get('id')) ? null : (int)$request->get('id');
		$text			= $request->get('text');
		$parentId		= (int)$request->get('parent');
		$oldParentId	= (int)$request->get('old_parent');
		$parent			= Menu::withDepth()->find($parentId) ?: null;
		$lvl			= $parent ? $parent->depth + 1 : 1;
		$position		= $request->get('position');
		$oldPosition	= $request->get('old_position');
		$result			= ['error' => true];

		switch($operation) {
			case 'get_node':
				if ( null === $id ) {
					$data = Menu::defaultOrder()
                        ->where('parent_id', null)
                        ->with(['menuItemType','descendants','children'])
                        ->get();
				} else {
					$data = Menu::with('menuItemType','descendants','children')
                        ->defaultOrder()
                        ->descendantsOf($id)->toTree();
				}
				$result = [];
				if( $data->count() > 0 ) {
					foreach( $data as $v ) {
						$result[] = [
							'id'		=> $v->id,
							'text'		=> $v->name,
							'children'	=> count($v->children) > 0,
						];
					}
				}
				break;
			case 'get_content':
				$node = Menu::with(['menuItemType','ancestors','children'])->find($id);
				if($node) {
					/**
					 * @var $nodeWithAncestors Collection
					 */
					if($node->ancestors->count()) {
						$nodeWithAncestors = $node->ancestors->pluck('name')->add($node->name);
					} else {
						$nodeWithAncestors = collect(['Top Level',$node->name]);
					}

					$result = [
						'nodeWithAncestors'	=> $nodeWithAncestors->toArray(),
						'id'	        => $node->id,
						'name'	        => $node->name,
						'icon'	        => $node->icon,
                        'fa_icon'	    => $node->fa_icon,
						'url'	        => $node->url,
						'menuItemType'	=> isset($node->menuItemType) ? $node->menuItemType : null,
						'is_published' 	=> isset($node->is_published) ? $node->is_published : null,
                        'api_enabled' 	=> isset($node->api_enabled) ? $node->api_enabled : null,
					];
				} else {
					$result = ['error' => 'no node found by id: ' . $id];
				}
				break;
			case 'create_node':
				$node = Menu::create([
					'parent_id'		=> $parentId,
					'name' 			=> $text,
					'lvl'			=> $lvl,
					'is_published'	=> 0,
                    'api_enabled'   => 0,
				]);
				$node->save();
				$result = [
					'id'	=> $node->id,
					'text'	=> $node->name,
					'parent'	=> $parentId,
					'position'	=> $position,
					'children'	=> (count($node->children) > 0 ) ? true : false,
				];
				break;
			case 'move_node':
				$node = Menu::with('menuItemType')->find($id);
				$node->lvl = $lvl;
				$moved = false;
				$msg = '';

				if( $oldParentId !== $parentId ) {
					// parent changed
					if($parent) {
						$node->appendToNode($parent);
						$moved = $node->moveNode($node->getKey(), $position);
						$msg = "moved on parent: $moved new pos: $position (oldParent: $oldParentId newParent: $parentId)";
					} else {
						$node->appendToNode($this->root);
						$moved = $node->moveNode($node->getKey(), $position);
						$msg = "moved on parent: $moved new pos: $position (oldParent: $oldParentId newParent: $parentId)";
					}
				} else {
					// move only in siblings
					if($oldPosition > $position) {
						// we move up
						$offset = $oldPosition - $position;
						$moved = $node->up($offset);
					} else {
						// we move down
						$offset = $position - $oldPosition;
						$moved = $node->down($offset);
					}
					$msg = "in siblings old: $oldPosition new: $position moved: $moved";
				}

				if($moved) {
					$node->save();
                }

				$result = [
					'id'		=> $node->id,
					'text'		=> $node->name,
					'position'	=> $position,
					'moved'		=> $moved,
					'children'	=> (count($node->children) > 0 ) ? true : false,
					'msg'		=> $msg,
				];
				break;
			case 'delete_node':
				$result = [
					'id' 		=> $id,
					'delete'	=> Menu::with(['menuItemType','descendants','children'])->delete(),
				];
                break;
			case 'rename_node':
				$node = Menu::with(['menuItemType','descendants','children'])->find($id);
				$node->name = $text;
				$node->slug = Str::slug($text, '-');
				$node->save();
				$result = [
					'id'	=> $node->id,
					'text'	=> $node->name,
					'position'	=> $position,
					'children'	=> (count($node->children) > 0 ) ? true : false,
				];
				break;
			case 'analyze':
				$result = Menu::countErrors();
				break;
			case 'fix':
				$result = Menu::fixTree();
                break;
		}
		return response()->json($result);
	}

	/**
	 * Store a newly created resource in storage.
	 * @param  Request  $request
	 * @return Response
	 */
	public function store() {

		if(Request::json()) {
			$data = request()->post();
			/**
			 * @var Menu $node
			 */
			$node = Menu::find($data['id']);
			$type = (int)$data['menuItemType'];
			try {
				// EXTERNAL LINK
				$node->url					= isset($data['url']) ? $data['url'] : null;
				$node->icon					= $data['icon'];
                $node->fa_icon				= $data['fa_icon'];
				$node->name					= $data['name'];
				$node->is_published 		= isset($data['is_published']) ? 1 : 0;
                $node->api_enabled 		    = isset($data['api_enabled']) ? 1 : 0;
				$node->menu_item_type_id	= $type;

				$result = $node->save();
				$response = ['result' => $result, 'node' => $node,];

			} catch(Exception $e) {
				$response = ['error' => $e->getMessage()];
			}
		}
		else {
			$response = ['error' => 'no valid ajax request!'];
		}

		return response()->json($response);
	}
}
