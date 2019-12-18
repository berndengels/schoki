<?php
/**
 * Newsletter.php
 *
 * @author    Bernd Engels
 * @created   15.06.19 12:50
 * @copyright Webwerk Berlin GmbH
 */

namespace App\Models;

use App\Models\Ext\HasUser;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\Newsletter
 *
 * @property int $id
 * @property int $tag_id
 * @property Carbon|null $updated_at
 * @property int|null $updated_by
 * @property Carbon $created_at
 * @property int $created_by
 * @property-read User $createdBy
 * @property-read AddressCategory $tag
 * @property-read User|null $updatedBy
 * @method static Builder|Newsletter newModelQuery()
 * @method static Builder|Newsletter newQuery()
 * @method static Builder|Newsletter query()
 * @method static Builder|Newsletter whereCreatedAt($value)
 * @method static Builder|Newsletter whereCreatedBy($value)
 * @method static Builder|Newsletter whereId($value)
 * @method static Builder|Newsletter whereTagId($value)
 * @method static Builder|Newsletter whereUpdatedAt($value)
 * @method static Builder|Newsletter whereUpdatedBy($value)
 * @mixin Eloquent
 */
class Newsletter extends Model {
	use HasUser;

	/**
	 * @var string
	 */
	protected $table = 'newsletter';
	/**
	 * @var array
	 */
	protected $fillable = ['id'];
	protected $dates = ['created_at','updated_at'];

	/**
	 * @return BelongsTo
	 */
	public function tag()
	{
		return $this->belongsTo(AddressCategory::class, 'tag_id', 'tag_id');
	}
}
