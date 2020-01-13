<?php

namespace App\Models;

use App\Models\Image;
use App\Models\Ext\HasUser;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use App\Repositories\EventPeriodicRepository;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Kyslik\ColumnSortable\Sortable;

/**
 * Class EventPeriodic
 *
 * @package App\Models
 * @property int $id
 * @property int|null $theme_id
 * @property int $category_id
 * @property int $periodic_position_id
 * @property int $periodic_weekday_id
 * @property int $created_by
 * @property int|null $updated_by
 * @property string $title
 * @property string|null $subtitle
 * @property array $description
 * @property array $links
 * @property string|null $event_date
 * @property string|null $event_time
 * @property int|null $price
 * @property Carbon $created_at
 * @property Carbon|null $updated_at
 * @property int|null $is_published
 * @property-read \Illuminate\Database\Eloquent\Collection|Audios[] $audios
 * @property-read int|null $audios_count
 * @property-read Category $category
 * @property-read User $createdBy
 * @property-read mixed $event_dates
 * @property-read \Illuminate\Database\Eloquent\Collection|Images[] $images
 * @property-read int|null $images_count
 * @property-read Theme|null $theme
 * @property-read User|null $updatedBy
 * @property-read \Illuminate\Database\Eloquent\Collection|Videos[] $videos
 * @property-read int|null $videos_count
 * @method static Builder|EventPeriodic newModelQuery()
 * @method static Builder|EventPeriodic newQuery()
 * @method static Builder|EventPeriodic query()
 * @method static Builder|EventPeriodic sortable($defaultParameters = null)
 * @method static Builder|EventPeriodic whereCategoryId($value)
 * @method static Builder|EventPeriodic whereCreatedAt($value)
 * @method static Builder|EventPeriodic whereCreatedBy($value)
 * @method static Builder|EventPeriodic whereDescription($value)
 * @method static Builder|EventPeriodic whereEventDate($value)
 * @method static Builder|EventPeriodic whereEventTime($value)
 * @method static Builder|EventPeriodic whereId($value)
 * @method static Builder|EventPeriodic whereIsPublished($value)
 * @method static Builder|EventPeriodic whereLinks($value)
 * @method static Builder|EventPeriodic wherePeriodicPositionId($value)
 * @method static Builder|EventPeriodic wherePeriodicWeekdayId($value)
 * @method static Builder|EventPeriodic wherePrice($value)
 * @method static Builder|EventPeriodic whereSubtitle($value)
 * @method static Builder|EventPeriodic whereThemeId($value)
 * @method static Builder|EventPeriodic whereTitle($value)
 * @method static Builder|EventPeriodic whereUpdatedAt($value)
 * @method static Builder|EventPeriodic whereUpdatedBy($value)
 * @mixin Eloquent
 */
class EventPeriodic extends Model
{
	use Sortable, HasUser;

    /**
     * @var array
     */
    public $sortable = [
		'category',
		'title',
		'created_at',
	];

    /**
     * @var string
     */
    protected $table = 'event_periodic';
    /**
     * @var array
     */
    protected $fillable = [
        'title',
        'subtitle',
        'category_id',
        'theme_id',
        'periodic_position',
        'periodic_weekday',
        'periodicDate',
        'description',
        'links',
        'event_time',
        'is_published',
    ];

    /**
     * @var array
     */
    protected $dates = ['created_at','updated_at'];
    /**
     * @var array
     */
    protected $appends = [
        'event_dates',
        'description_sanitized',
    ];

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
     * @return HasMany
     */
    public function audios()
    {
        return $this->hasMany('App\Models\Audios');
    }

    /**
     * @return HasMany
     */
    public function videos()
    {
        return $this->hasMany('App\Models\Videos');
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

	public function getDescriptionSanitizedAttribute()
    {
        $wrapper = '<div class="row embed-responsive-wrapper text-center"><div class="embed-responsive embed-responsive-16by9 m-0 p-0">%%</div></div>';
        $descriptionSanitized = preg_replace("/(<iframe[^>]+><\/iframe>)/i", str_replace('%%','$1', $wrapper), $this->description);
        return $descriptionSanitized;
    }

    /**
     * @return mixed
     */
    public function getEventDatesAttribute()
    {
        $repo = new EventPeriodicRepository();
        $data = $repo->getPeriodicDates($this, true, true);
        return $data;
    }
}
