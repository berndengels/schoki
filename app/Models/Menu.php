<?php

namespace App\Models;

use Eloquent;
use Kalnoy\Nestedset\Collection;
use Kalnoy\Nestedset\NodeTrait;
use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\QueryBuilder;

/**
 * App\Models\Menu
 *
 * @property int $id
 * @property int|null $parent_id
 * @property int|null $menu_item_type_id
 * @property string $name
 * @property string|null $icon
 * @property string|null $fa_icon
 * @property string|null $url
 * @property int $lft
 * @property int $rgt
 * @property int $lvl
 * @property int $is_published
 * @property int|null $api_enabled
 * @property-read Collection<int, Menu> $children
 * @property-read int|null $children_count
 * @property-read MenuItemType|null $menuItemType
 * @property Menu|null $parent
 * @method static Collection<int, static> all($columns = ['*'])
 * @method static QueryBuilder|Menu ancestorsAndSelf($id, array $columns = [])
 * @method static QueryBuilder|Menu ancestorsOf($id, array $columns = [])
 * @method static QueryBuilder|Menu applyNestedSetScope(?string $table = null)
 * @method static QueryBuilder|Menu countErrors()
 * @method static QueryBuilder|Menu d()
 * @method static QueryBuilder|Menu defaultOrder(string $dir = 'asc')
 * @method static QueryBuilder|Menu descendantsAndSelf($id, array $columns = [])
 * @method static QueryBuilder|Menu descendantsOf($id, array $columns = [], $andSelf = false)
 * @method static QueryBuilder|Menu fixSubtree($root)
 * @method static QueryBuilder|Menu fixTree($root = null)
 * @method static Collection<int, static> get($columns = ['*'])
 * @method static QueryBuilder|Menu getNodeData($id, $required = false)
 * @method static QueryBuilder|Menu getPlainNodeData($id, $required = false)
 * @method static QueryBuilder|Menu getTotalErrors()
 * @method static QueryBuilder|Menu hasChildren()
 * @method static QueryBuilder|Menu hasParent()
 * @method static QueryBuilder|Menu isBroken()
 * @method static QueryBuilder|Menu leaves(array $columns = [])
 * @method static QueryBuilder|Menu makeGap(int $cut, int $height)
 * @method static QueryBuilder|Menu moveNode($key, $position)
 * @method static QueryBuilder|Menu newModelQuery()
 * @method static QueryBuilder|Menu newQuery()
 * @method static QueryBuilder|Menu orWhereAncestorOf(bool $id, bool $andSelf = false)
 * @method static QueryBuilder|Menu orWhereDescendantOf($id)
 * @method static QueryBuilder|Menu orWhereNodeBetween($values)
 * @method static QueryBuilder|Menu orWhereNotDescendantOf($id)
 * @method static QueryBuilder|Menu query()
 * @method static QueryBuilder|Menu rebuildSubtree($root, array $data, $delete = false)
 * @method static QueryBuilder|Menu rebuildTree(array $data, $delete = false, $root = null)
 * @method static QueryBuilder|Menu reversed()
 * @method static QueryBuilder|Menu root(array $columns = [])
 * @method static QueryBuilder|Menu whereAncestorOf($id, $andSelf = false, $boolean = 'and')
 * @method static QueryBuilder|Menu whereAncestorOrSelf($id)
 * @method static QueryBuilder|Menu whereApiEnabled($value)
 * @method static QueryBuilder|Menu whereDescendantOf($id, $boolean = 'and', $not = false, $andSelf = false)
 * @method static QueryBuilder|Menu whereDescendantOrSelf(string $id, string $boolean = 'and', string $not = false)
 * @method static QueryBuilder|Menu whereFaIcon($value)
 * @method static QueryBuilder|Menu whereIcon($value)
 * @method static QueryBuilder|Menu whereId($value)
 * @method static QueryBuilder|Menu whereIsAfter($id, $boolean = 'and')
 * @method static QueryBuilder|Menu whereIsBefore($id, $boolean = 'and')
 * @method static QueryBuilder|Menu whereIsLeaf()
 * @method static QueryBuilder|Menu whereIsPublished($value)
 * @method static QueryBuilder|Menu whereIsRoot()
 * @method static QueryBuilder|Menu whereLft($value)
 * @method static QueryBuilder|Menu whereLvl($value)
 * @method static QueryBuilder|Menu whereMenuItemTypeId($value)
 * @method static QueryBuilder|Menu whereName($value)
 * @method static QueryBuilder|Menu whereNodeBetween($values, $boolean = 'and', $not = false, $query = null)
 * @method static QueryBuilder|Menu whereNotDescendantOf($id)
 * @method static QueryBuilder|Menu whereParentId($value)
 * @method static QueryBuilder|Menu whereRgt($value)
 * @method static QueryBuilder|Menu whereUrl($value)
 * @method static QueryBuilder|Menu withDepth(string $as = 'depth')
 * @method static QueryBuilder|Menu withoutRoot()
 * @mixin Eloquent
 */
class Menu extends Model
{
	use NodeTrait;

    protected $table    = 'menu';
    public $timestamps  = false;
	protected $guarded = ['id'];
    protected $with     = ['menuItemType'];

	public function menuItemType()
	{
		return $this->belongsTo(MenuItemType::class);
	}

	public function getLftName()
    {
        return 'lft';
    }

    public function getRgtName()
    {
        return 'rgt';
    }

    public function getDepthName()
    {
        return 'lvl';
    }

    public function getParentIdName()
    {
        return 'parent_id';
    }

// Specify parent id attribute mutator
    public function setParentAttribute($value)
    {
        $this->setParentIdAttribute($value);
    }
}
