<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
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
 * @property-read Collection|Menu[] $children
 * @property-read int|null $children_count
 * @property-read MenuItemType|null $menuItemType
 * @property Menu|null $parent
 * @method static Builder|Menu d()
 * @method static QueryBuilder|Menu newModelQuery()
 * @method static QueryBuilder|Menu newQuery()
 * @method static QueryBuilder|Menu query()
 * @method static Builder|Menu whereFaIcon($value)
 * @method static Builder|Menu whereIcon($value)
 * @method static Builder|Menu whereId($value)
 * @method static Builder|Menu whereIsPublished($value)
 * @method static Builder|Menu whereLft($value)
 * @method static Builder|Menu whereLvl($value)
 * @method static Builder|Menu whereMenuItemTypeId($value)
 * @method static Builder|Menu whereName($value)
 * @method static Builder|Menu whereParentId($value)
 * @method static Builder|Menu whereRgt($value)
 * @method static Builder|Menu whereUrl($value)
 * @mixin Eloquent
 * @property int|null $api_enabled
 * @method static Builder|Menu whereApiEnabled($value)
 */
class Menu extends Model
{
	use NodeTrait;

    protected $table    = 'menu';
    public $timestamps  = false;
    protected $fillable = ['parent_id','name','lvl','lft','rgt','url','menuItemType','is_published'];
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
