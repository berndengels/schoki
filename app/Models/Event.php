<?php

namespace App\Models;

use Eloquent;
use Carbon\Carbon;
use App\Models\Image;
use App\Helper\MyDate;
use App\Models\Ext\HasUser;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Kyslik\ColumnSortable\Sortable;
use Illuminate\Database\Eloquent\Builder;
use App\Repositories\EventEntityRepository;
use App\Repositories\EventPeriodicRepository;

/**
 * Class Event
 *
 * @property int $id
 * @property int|null $theme_id
 * @property int $category_id
 * @property int $created_by
 * @property int|null $updated_by
 * @property string $title
 * @property string|null $subtitle
 * @property array $description
 * @property array $links
 * @property string|null $ticketlink
 * @property \Illuminate\Support\Carbon $event_date
 * @property string|null $event_time
 * @property int|null $price
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property bool|null $is_periodic
 * @property bool|null $is_published
 * @property-read Collection<int, Audios> $audios
 * @property-read int|null $audios_count
 * @property-read Category|null $category
 * @property-read User|null $createdBy
 * @property-read mixed $description_sanitized
 * @property-read mixed $description_text
 * @property-read mixed $description_without_video
 * @property-read mixed $event_link
 * @property-read Collection<int, Images> $images
 * @property-read int|null $images_count
 * @property-read Theme|null $theme
 * @property-read User|null $updatedBy
 * @property-read Collection<int, Videos> $videos
 * @property-read int|null $videos_count
 * @method static Builder|Event allActual()
 * @method static Builder|Event byCategorySlug($slug, $sinceToday = true)
 * @method static Builder|Event byThemeSlug($slug, $sinceToday = true)
 * @method static Builder|Event mergedByCategorySlug($slug, $sinceToday = true)
 * @method static Builder|Event mergedByDate($date)
 * @method static Builder|Event mergedByDateAndCategory($date, $slug)
 * @method static Builder|Event mergedByDateAndTheme($date, $slug)
 * @method static Builder|Event mergedByThemeSlug($slug, $sinceToday = true)
 * @method static Builder|Event newModelQuery()
 * @method static Builder|Event newQuery()
 * @method static Builder|Event query()
 * @method static Builder|Event sortable($defaultParameters = null)
 * @method static Builder|Event whereCategoryId($value)
 * @method static Builder|Event whereCreatedAt($value)
 * @method static Builder|Event whereCreatedBy($value)
 * @method static Builder|Event whereDescription($value)
 * @method static Builder|Event whereEventDate($value)
 * @method static Builder|Event whereEventTime($value)
 * @method static Builder|Event whereId($value)
 * @method static Builder|Event whereIsPeriodic($value)
 * @method static Builder|Event whereIsPublished($value)
 * @method static Builder|Event whereLinks($value)
 * @method static Builder|Event wherePrice($value)
 * @method static Builder|Event whereSubtitle($value)
 * @method static Builder|Event whereThemeId($value)
 * @method static Builder|Event whereTicketlink($value)
 * @method static Builder|Event whereTitle($value)
 * @method static Builder|Event whereUpdatedAt($value)
 * @method static Builder|Event whereUpdatedBy($value)
 * @mixin Eloquent
 */
class Event extends Model
{
	use HasUser, Sortable;

	public $sortable = [
        'event_date',
		'title',
	];

	/**
     * @var string
     */
    protected $table = 'event';
    /**
     * @var array
     */
    protected $guarded = ['id'];
    protected $with = ['images','createdBy','updatedBy'];
    protected $appends = [
        'descriptionSanitized',
        'descriptionText',
        'descriptionWithoutVideo',
        'eventLink',
    ];
    protected $dates = [
        'created_at',
        'updated_at',
//        'event_date',
    ];
    protected $casts = [
        'event_date'    => 'date:Y-m-d',
        'is_periodic'   => 'bool',
        'is_published'  => 'bool',
    ];
    protected $dateFormat = 'Y-m-d';

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

	public function getEventLinkAttribute()
	{
		return route('public.event.eventsShow', $this);
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
	 * @param string $value
	 * @return array
	 */
	public function getDescriptionAttribute($value = '')
	{
		if('' !== $value) {
			return preg_replace(['/^<p>(<br[ ]?[\/]?>){1,}/i','/(<br[ ]?[\/]?>){1,}<\/p>$/i'],['<p>','</p>'], trim($value));
		}
	}


	public function scopeAllActual(Builder $query)
	{
		return $query
			->with(['category','theme'])
			->where('is_published', 1)
			->whereDate('event_date','>=', MyDate::getUntilValidDate())
			->orderBy('event_date')
		;
	}

	public static function allActualMerged()
	{
		$repo			= new EventPeriodicRepository();
		$repoEntity		= new EventEntityRepository();

		$periodicEvents	= $repo->getAllPeriodicDates(true, true);
		$datedEvents	= self::allActual()
            ->get()
            ->keyBy(fn($item) => $item['event_date']->format('Y-m-d'));

		$mapped	= $repoEntity->mapToEventEntityCollection($datedEvents);
		$merged	= $periodicEvents->merge($mapped)->sortKeys();

        return $merged;
	}

	public static function eventsForNewsletter(Carbon $from, Carbon $until)
	{
		return self::allActualMerged()->filter(function ($event) use ($from, $until) {
			return ($event->getEventDate()->between($from, $until));
		});
	}

	public function scopeByCategorySlug(Builder $query, $slug, $sinceToday = true)
	{
		return $query
			->with(['category','theme'])
			->where('is_published', 1)
			->when($sinceToday, fn($query) => $query->whereDate('event_date','>=', MyDate::getUntilValidDate()))
			->whereHas('category', fn($query) => $query->where('slug', $slug));
	}

	public function scopeByThemeSlug(Builder $query, $slug, $sinceToday = true)
	{
		return $query
			->with(['category','theme'])
			->where('is_published', 1)
			->when($sinceToday, fn($query) => $query->whereDate('event_date','>=', MyDate::getUntilValidDate()))
			->whereHas('theme', function($query) use ($slug) {
				$query->where('slug', $slug);
			});
	}

	public function scopeMergedByCategorySlug(Builder $query, $slug, $sinceToday = true)
	{
		$repo 		= new EventPeriodicRepository();
		$repoEntity	= new EventEntityRepository();

		$periodicEvents	= $repo->getAllPeriodicDatesByCategory($slug);
		$datedEvents = $this->scopeByCategorySlug($query, $slug, $sinceToday)
            ->get()
            ->keyBy(fn($item) => $item['event_date']->format('Y-m-d'));

		$mappedEvents = $repoEntity->mapToEventEntityCollection($datedEvents);
		$merged = $periodicEvents->merge($mappedEvents)->sortKeys();

		return $merged;
	}

	public function scopeMergedByDate(Builder $query, $date )
	{
		$repo 	= new EventPeriodicRepository();
		$event	= $query->whereDate('event_date', $date)->first();

		if( $event ) {
			return $event;
		}

		$periodicEvent	= $repo->getPeriodicEventByDate($date);
		if( $periodicEvent ) {
			return $periodicEvent;
		}
		return null;
	}

	public function scopeMergedByDateAndCategory(Builder $query, $date, $slug )
	{
		$repo 		= new EventPeriodicRepository();
		$repoEntity	= new EventEntityRepository();

		$event = $query->whereDate('event_date', $date)
			->where('is_published', 1)
			->whereHas('category', function($query) use ($slug) {
				$query->where('slug', $slug);
			})
			->first();
		if($event) {
			$entity = $repoEntity->mapToEventEntity($event, $date);
			if($entity) {
				return $entity;
			}
		}

		$periodicEvent	= $repo->getPeriodicEventByDateAndCategory($date, $slug);
		if($periodicEvent) {
			return $periodicEvent;
		}
		return null;
	}

	public function scopeMergedByDateAndTheme(Builder $query, $date, $slug )
	{
		$repo 		= new EventPeriodicRepository();
		$repoEntity	= new EventEntityRepository();

		$event = $query->whereDate('event_date', $date)
			->where('is_published', 1)
			->whereHas('theme', function($query) use ($slug) {
				$query->where('slug', $slug);
			})
			->first();
		if($event) {
			$entity = $repoEntity->mapToEventEntity($event, $date);
			if($entity) {
				return $entity;
			}
		}

		$periodicEvent	= $repo->getPeriodicEventByDateAndCategory($date, $slug);
		if($periodicEvent) {
			return $periodicEvent;
		}
		return null;
	}

	public function scopeMergedByThemeSlug(Builder $query, $slug, $sinceToday = true)
	{
		$repo 		= new EventPeriodicRepository();
		$repoEntity	= new EventEntityRepository();

		$periodicEvents	= $repo->getAllPeriodicDatesByTheme($slug);
		$datedEvents = $this->scopeByThemeSlug($query, $slug, $sinceToday)->get()->keyBy('event_date');

		$mappedEvents = $repoEntity->mapToEventEntityCollection($datedEvents);
		$merged = $periodicEvents->merge($mappedEvents)->sortKeys();

		return $merged;
	}
}
