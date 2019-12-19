<?php

namespace App\Models;

use App\Models\Image;
use App\Models\Ext\HasUser;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Kyslik\ColumnSortable\Sortable;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Event
 *
 * @property int $id
 * @property int|null $theme_id
 * @property int $category_id
 * @property string $title
 * @property string|null $subtitle
 * @property array $description
 * @property array $links
 * @property Carbon $created_at
 * @property int $created_by
 * @property Carbon|null $updated_at
 * @property int|null $updated_by
 * @property-read Category $category
 * @property-read User $createdBy
 * @property-read Collection|Images[] $images
 * @property-read int|null $images_count
 * @property-read Theme|null $theme
 * @property-read User|null $updatedBy
 * @method static Builder|EventTemplate newModelQuery()
 * @method static Builder|EventTemplate newQuery()
 * @method static Builder|EventTemplate query()
 * @method static Builder|EventTemplate sortable($defaultParameters = null)
 * @method static Builder|EventTemplate whereCategoryId($value)
 * @method static Builder|EventTemplate whereCreatedAt($value)
 * @method static Builder|EventTemplate whereCreatedBy($value)
 * @method static Builder|EventTemplate whereDescription($value)
 * @method static Builder|EventTemplate whereId($value)
 * @method static Builder|EventTemplate whereLinks($value)
 * @method static Builder|EventTemplate whereSubtitle($value)
 * @method static Builder|EventTemplate whereThemeId($value)
 * @method static Builder|EventTemplate whereTitle($value)
 * @method static Builder|EventTemplate whereUpdatedAt($value)
 * @method static Builder|EventTemplate whereUpdatedBy($value)
 * @mixin Eloquent
 */
class EventTemplate extends Model
{
	use Sortable, HasUser;

	/**
     * @var string
     */
    protected $table = 'event_template';
    /**
     * @var array
     */
    protected $fillable = [
        'category_id',
        'theme_id',
        'title',
        'subtitle',
        'description',
        'links',
    ];
    protected $dates = ['created_at','updated_at'];
	protected $eventLink;
	public $descriptionSanitized;

	public static function boot() {
		parent::boot();
		Event::retrieved(function($entity) {
			$wrapper = '<div class="row embed-responsive-wrapper text-center"><div class="embed-responsive embed-responsive-16by9 m-0 p-0">%%</div></div>';
			$entity->descriptionSanitized = preg_replace("/(<iframe[^>]+><\/iframe>)/i", str_replace('%%','$1', $wrapper), $entity->description);
		});
	}

	/**
     * @return BelongsTo
     */
    public function category()
    {
        return $this->belongsTo('App\Models\Category');
    }

    /**
     * @return BelongsTo
     */
    public function theme()
    {
        return $this->belongsTo('App\Models\Theme');
    }

    /**
     * @return HasMany
     */
    public function images()
    {
        return $this->hasMany('App\Models\Images');
    }

    /**
     * @param string $value
     * @return array
     */
    public function getLinksAttribute($value = '')
    {
        if('' !== $value) {
            return collect(preg_split("/[\n\r]+/", $value));
        }
    }

	/**
	 * @param string $value
	 * @return array
	 */
	public function getDescriptionAttribute($value = '')
	{
		if('' !== $value) {
			return preg_replace(['/^<p>(<br[ ]?[\/]?>){1,}/i','/(<br[ ]?[\/]?>){1,}<\/p>$/i'],['<p>','</p>'], trim($value));
		}
	}
}
