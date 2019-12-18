<?php
/**
 * MenuItemType.php
 *
 * @author    Bernd Engels
 * @created   05.04.19 16:21
 * @copyright Webwerk Berlin GmbH
 */
namespace App\Models;

use App\Models\Menu;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Kalnoy\Nestedset\Collection;

/**
 * App\Models\MenuItemType
 *
 * @property int $id
 * @property string $type
 * @property string $label
 * @property-read Collection|\App\Models\Menu[] $menus
 * @property-read int|null $menus_count
 * @method static Builder|MenuItemType newModelQuery()
 * @method static Builder|MenuItemType newQuery()
 * @method static Builder|MenuItemType query()
 * @method static Builder|MenuItemType whereId($value)
 * @method static Builder|MenuItemType whereLabel($value)
 * @method static Builder|MenuItemType whereType($value)
 * @mixin Eloquent
 */
class MenuItemType extends Model {

	protected $table = 'menu_item_type';
	public $timestamps = false;

	protected $fillable = ['type','name'];

	/**
	 * @return HasMany
	 */
	public function menus()
	{
		return $this->hasMany(Menu::class);
	}
}
