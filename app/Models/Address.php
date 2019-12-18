<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Kyslik\ColumnSortable\Sortable;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Address
 *
 * @property int $id
 * @property int $address_category_id
 * @property string $email
 * @property string $token
 * @property int|null $info_on_changes
 * @property Carbon $created_at
 * @property Carbon|null $updated_at
 * @property-read AddressCategory $addressCategory
 * @method static Builder|Address newModelQuery()
 * @method static Builder|Address newQuery()
 * @method static Builder|Address query()
 * @method static Builder|Address sortable($defaultParameters = null)
 * @method static Builder|Address whereAddressCategoryId($value)
 * @method static Builder|Address whereCreatedAt($value)
 * @method static Builder|Address whereEmail($value)
 * @method static Builder|Address whereId($value)
 * @method static Builder|Address whereInfoOnChanges($value)
 * @method static Builder|Address whereToken($value)
 * @method static Builder|Address whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Address extends Model
{
	use Sortable;

	protected $table = 'address';
    protected $fillable = ['address_category_id','email','token'];
    public $timestamps = true;
	public $sortable = [
		'addressCategory',
		'email',
		'created_at',
	];

	/**
	 * @return BelongsTo
	 */
	public function addressCategory()
	{
		return $this->belongsTo(AddressCategory::class, 'address_category_id', 'id');
	}
}
