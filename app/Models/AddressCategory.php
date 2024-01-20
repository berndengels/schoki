<?php

namespace App\Models;

use Eloquent;
use Kyslik\ColumnSortable\Sortable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\AddressCategory
 *
 * @property int $id
 * @property int|null $tag_id
 * @property string $name
 * @property-read Collection<int, Address> $addresses
 * @property-read int|null $addresses_count
 * @property-read Collection<int, Newsletter> $newsletters
 * @property-read int|null $newsletters_count
 * @method static Builder|AddressCategory newModelQuery()
 * @method static Builder|AddressCategory newQuery()
 * @method static Builder|AddressCategory query()
 * @method static Builder|AddressCategory sortable($defaultParameters = null)
 * @method static Builder|AddressCategory whereId($value)
 * @method static Builder|AddressCategory whereName($value)
 * @method static Builder|AddressCategory whereTagId($value)
 * @mixin Eloquent
 */
class AddressCategory extends Model
{
	use Sortable;

	protected $table = 'address_category';
    protected $fillable = ['name'];
    public $timestamps = false;

    public function delete()
    {
        $count = $this->addresses->count();
        if($count > 0) {
            return back()
                ->with('error','Es existieren noch Adressen ('.$count.') mit dieser Kategorie! Bitte vorher löschen.');
        }
        $count = $this->newsletters->count();
        if($count > 0) {
            return back()
                ->with('error','Es existieren noch Newsletter ('.$count.') mit dieser Kategorie! Bitte vorher löschen.');
        }
        return parent::delete();
    }

    /**
	 * @return HasMany
	 */
	public function addresses()
	{
		return $this->hasMany(Address::class, 'address_category_id', 'id');
	}

	/**
	 * @return HasMany
	 */
	public function newsletters()
	{
		return $this->hasMany(Newsletter::class, 'tag_id', 'tag_id');
	}
}
