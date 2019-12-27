<?php
/**
 * NestedSet.php
 *
 * @author    Bernd Engels
 * @created   03.04.19 15:19
 * @copyright Webwerk Berlin GmbH
 */

namespace App\Repositories;

use App\Models\Menu;
use App\Models\MenuItemType;
use Kalnoy\Nestedset\NodeTrait;

class MenuRepository {

	/**
	 * @var Menu
	 */
	protected $model;

	public function __construct() {
		$this->model = Menu::class;
	}

	public function getTree()
	{
		return Menu::get()->toTree();
	}

    public function getPublishedTree()
    {
        return Menu::whereIsPublished(1)->get()->toTree();
    }

	public function getNode( $id, $withDepth = true)
	{
		return $withDepth ? Menu::withDepth()->find($id) : Menu::find($id);
	}

	public function getChildren( $id )
	{
		return $this->getNode($id, true)->children;
	}

	public function getPath( $id )
	{
		return $this->getNode($id)->depth;
	}

	public function getTreeByName($name) {
        return Menu::whereName($name)->get()->toTree();
    }

    public function getTopMenu($forApi = false) {
        $topMenuType    = MenuItemType::where('type', 'topMenuRoot')->first();
        $topMenu 	    = Menu::select(['id'])->where('menu_item_type_id', $topMenuType->id)->first();

        $query = Menu::where('is_published',1);
        if($forApi) {
            $query = $query->where('api_enabled', 1);
        }
        $query->with('menuItemType');

        return $query
            ->defaultOrder()
            ->descendantsOf($topMenu->id)
            ->toTree()
            ;
    }

    public function getBottomMenu($forApi = false) {
        $bottomMenuType	= MenuItemType::where('type', 'bottomMenuRoot')->first();
        $bottomMenu 	= Menu::select(['id'])->where('menu_item_type_id', $bottomMenuType->id)->first();

        $query = Menu::where('is_published',1);
        if($forApi) {
            $query = $query->where('api_enabled', 1);
        }
        $query->with('menuItemType');

        return $query
            ->defaultOrder()
            ->descendantsOf($bottomMenu->id)->where('is_published',1)
            ->toTree()
            ;
    }
}
