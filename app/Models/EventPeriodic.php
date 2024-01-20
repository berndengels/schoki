<?php

namespace App\Models;

use Eloquent;
use App\Models\Image;
use App\Models\Ext\HasUser;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use App\Repositories\EventPeriodicRepository;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Kyslik\ColumnSortable\Sortable;

/**
 * Class EventPeriodic
 *
 * @property int $id
 * @property int|null $theme_id
 * @property int $category_id
 * @property string $periodic_position
 * @property string $periodic_weekday
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
 * @property bool|null $is_published
 * @property-read Collection<int, Audios> $audios
 * @property-read int|null $audios_count
 * @property-read Category|null $category
 * @property-read User|null $createdBy
 * @property-read mixed $description_sanitized
 * @property-read mixed $description_text
 * @property-read mixed $description_without_video
 * @property-read mixed $event_dates
 * @property-read mixed $period
 * @property-read mixed $position
 * @property-read mixed $weekday
 * @property-read Collection<int, Images> $images
 * @property-read int|null $images_count
 * @property-read Theme|null $theme
 * @property-read User|null $updatedBy
 * @property-read Collection<int, Videos> $videos
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
 * @method static Builder|EventPeriodic wherePeriodicPosition($value)
 * @method static Builder|EventPeriodic wherePeriodicWeekday($value)
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
		'category_id',
		'title',
		'created_at',
	];

    /**
     * @var string
     */
    protected $table = 'event_periodic';
    protected $with = ['images','createdBy','updatedBy'];
    /**
     * @var array
     */
    protected $guarded = ['id'];
    protected $casts = [
        'is_published'  => 'bool',
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
        'position',
        'weekday',
        'period',
        'descriptionSanitized',
        'descriptionText',
        'descriptionWithoutVideo',
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

    public function getPositionAttribute()
    {
        $position = config('event.periodicPosition');
        return $position[$this->periodic_position] ?? null;
    }

    public function getWeekdayAttribute()
    {
        $weekday = config('event.periodicWeekday');
        return $weekday[$this->periodic_weekday] ?? null;
    }

    public function getPeriodAttribute()
    {
//        $weekday = config('event.periodicWeekday');
        return $this->position .' '. $this->weekday;
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
        if($this->description) {
            $wrapper = '<div class="row embed-responsive-wrapper text-center"><div class="embed-responsive embed-responsive-16by9 m-0 p-0">%%</div></div>';
            return preg_replace(
                "/(<iframe[^>]+><\/iframe>)/i",
                str_replace('%%','$1', $wrapper),
                $this->description
            );
        }
        return null;
    }

    public function getDescriptionTextAttribute()
    {
        if($this->description) {
            return strip_tags(preg_replace('/<br[ ]?[\/]?>/i',"\n", $this->description));
        }
    }

    public function getDescriptionWithoutVideoAttribute()
    {
        return preg_replace(
            "/<iframe[^>]+>(.*)<\/iframe>/i",
            '',
            $this->description
        );
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
