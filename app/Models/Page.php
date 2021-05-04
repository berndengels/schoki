<?php

namespace App\Models;

use App\Models\Ext\HasUser;
use App\Models\Image;
use App\Models\Audios;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Kyslik\ColumnSortable\Sortable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Event
 *
 * @property int $id
 * @property int $created_by
 * @property int|null $updated_by
 * @property string $title
 * @property string $slug
 * @property array $body
 * @property Carbon $created_at
 * @property Carbon|null $updated_at
 * @property int|null $is_published
 * @property-read Collection|\App\Models\Audios[] $audios
 * @property-read int|null $audios_count
 * @property-read User $createdBy
 * @property-read User|null $updatedBy
 * @method static Builder|Page newModelQuery()
 * @method static Builder|Page newQuery()
 * @method static Builder|Page query()
 * @method static Builder|Page sortable($defaultParameters = null)
 * @method static Builder|Page whereBody($value)
 * @method static Builder|Page whereCreatedAt($value)
 * @method static Builder|Page whereCreatedBy($value)
 * @method static Builder|Page whereId($value)
 * @method static Builder|Page whereIsPublished($value)
 * @method static Builder|Page whereSlug($value)
 * @method static Builder|Page whereTitle($value)
 * @method static Builder|Page whereUpdatedAt($value)
 * @method static Builder|Page whereUpdatedBy($value)
 * @mixin Eloquent
 */
class Page extends Model
{
	use Sortable, HasUser;

	public $sortable = [
		'title',
		'created_at',
	];
	/**
     * @var string
     */
    protected $table = 'page';
    /**
     * @var array
     */
    protected $fillable = ['title', 'body', 'created_by', 'updated_by'];
	protected $dates = ['created_at','updated_at'];
	protected $with = ['audios'];

    public static function boot() {
        parent::boot();

		Page::saving(function($entity) {
			$entity->slug = Str::slug( str_replace('.','-',$entity->title), '-', 'de');
		});
		Page::creating(function($entity) {
			$entity->slug = Str::slug(str_replace('.','-',$entity->title), '-', 'de');
		});
		Page::updating(function($entity) {
			$entity->slug = Str::slug(str_replace('.','-',$entity->title), '-', 'de');
		});
    }

	/**
	 * @return HasMany
	 */
	public function audios()
	{
		return $this->hasMany(Audios::class);
	}

	/**
	 * @param string $value
	 * @return array
	 */
	public function getBodyAttribute($value = '')
	{
		if('' !== $value) {
            $wrapper = '<div class="row embed-responsive-wrapper text-center"><div class="embed-responsive embed-responsive-16by9 m-0 p-0">%%</div></div>';
            $sanitized = preg_replace("/(<iframe[^>]+><\/iframe>)/i", str_replace('%%','$1', $wrapper), trim($value));
			return preg_replace(['/^<p>(<br[ ]?[\/]?>){1,}/i','/(<br[ ]?[\/]?>){1,}<\/p>$/i'],['<p>','</p>'], $sanitized);
		}
	}
}
