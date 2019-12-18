<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\Image;
use App\Helper\MyDate;
use App\Models\Ext\HasUser;
use Eloquent;
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
 * @property string $event_date
 * @property string|null $event_time
 * @property int|null $price
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $is_periodic
 * @property int|null $is_published
 * @property-read Collection|Audios[] $audios
 * @property-read int|null $audios_count
 * @property-read Category $category
 * @property-read User $createdBy
 * @property-read mixed $event_link
 * @property-read Collection|Images[] $images
 * @property-read int|null $images_count
 * @property-read Theme|null $theme
 * @property-read User|null $updatedBy
 * @property-read Collection|Videos[] $videos
 * @property-read int|null $videos_count
 * @method static Builder|Event allActual()
 * @method static Builder|Event allActualMerged()
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
 * @method static Builder|Event whereTitle($value)
 * @method static Builder|Event whereUpdatedAt($value)
 * @method static Builder|Event whereUpdatedBy($value)
 * @mixin Eloquent
 */
class Event extends Model
{
	use Sortable, HasUser;

	public $sortable = [
		'category',
		'title',
		'event_date',
	];

	/**
     * @var string
     */
    protected $table = 'event';
    /**
     * @var array
     */
    protected $fillable = [
        'title',
        'subtitle',
        'category_id',
        'theme_id',
        'description',
        'links',
        'event_date',
        'event_time',
        'is_periodic',
        'is_published',
    ];
    protected $dates = ['created_at','updated_at'];
	protected $eventLink;
	public $descriptionSanitized;
	public $descriptionText;
	public $testData;

	public static function boot() {
		parent::boot();
		Event::retrieved(function($entity) {
			$wrapper = '<div class="row embed-responsive-wrapper text-center"><div class="embed-responsive embed-responsive-16by9 m-0 p-0">%%</div></div>';
			$entity->descriptionSanitized = preg_replace("/(<iframe[^>]+><\/iframe>)/i", str_replace('%%','$1', $wrapper), $entity->description);
			$entity->descriptionText = strip_tags(preg_replace('/<br[ ]?[\/]?>/i',"\n", $entity->description));
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
		return route('events.show', $this);
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
		$result = $query
			->with(['category','theme'])
			->where('is_published', 1)
			->whereDate('event_date','>=', MyDate::getUntilValidDate())
			->orderBy('event_date')
		;

		return $result;
	}

	public function scopeAllActualMerged()
	{
		$repo			= new EventPeriodicRepository();
		$repoEntity		= new EventEntityRepository();

		$periodicEvents	= $repo->getAllPeriodicDates(true, true);
		$datedEvents	= self::allActual()->get()->keyBy('event_date');

		$mapped	= $repoEntity->mapToEventEntityCollection($datedEvents);
		$merged	= $periodicEvents->merge($mapped)->sortKeys();

		return $merged;
	}

	public static function eventsForNewsletter(Carbon $from, Carbon $until)
	{
		$filtered = self::allActualMerged()->filter(function ($event) use ($from, $until) {
			return ($event->getEventDate()->between($from, $until));
		});
		return $filtered;
	}

	/*
		public function scopeAllActualByDateKey(Builder $query, $slug = null)
		{
			$result = $query
				->where('event_date','>=', MyDate::getUntilValidDate())
				->when($slug, function($query) use ($slug) {
					return $query->where('slug', $slug);
				});

			return $result;
		}
	*/
	public function scopeByCategorySlug(Builder $query, $slug, $sinceToday = true)
	{
		$result = $query
			->with(['category','theme'])
			->where('is_published', 1)
			->when($sinceToday, function($query) {
				return $query->whereDate('event_date','>=', MyDate::getUntilValidDate());
			})
			->whereHas('category', function($query) use ($slug) {
				$query->where('slug', $slug);
			});
		return $result;
	}

	public function scopeByThemeSlug(Builder $query, $slug, $sinceToday = true)
	{
		$result = $query
			->with(['category','theme'])
			->where('is_published', 1)
			->when($sinceToday, function($query) {
				return $query->whereDate('event_date','>=', MyDate::getUntilValidDate());
			})
			->whereHas('theme', function($query) use ($slug) {
				$query->where('slug', $slug);
			});

		return $result;
	}

	public function scopeMergedByCategorySlug(Builder $query, $slug, $sinceToday = true)
	{
		$repo 		= new EventPeriodicRepository();
		$repoEntity	= new EventEntityRepository();

		$periodicEvents	= $repo->getAllPeriodicDatesByCategory($slug);
		$datedEvents = $this->scopeByCategorySlug($query, $slug, $sinceToday)->get()->keyBy('event_date');

		$mappedEvents = $repoEntity->mapToEventEntityCollection($datedEvents);
//		$merged = $periodicEvents->merge($mappedEvents)->sortKeys()->paginate(config('event.paginationLimit'));
		$merged = $periodicEvents->merge($mappedEvents)->sortKeys();

		return $merged;
	}

	public function scopeMergedByDate(Builder $query, $date )
	{
		$repo 	= new EventPeriodicRepository();
		$event	= self::whereDate('event_date', $date)->first();

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

		$event = self::whereDate('event_date', $date)
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

		$event = self::whereDate('event_date', $date)
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
//		$merged = $periodicEvents->merge($mappedEvents)->sortKeys()->paginate(config('event.paginationLimit'));
		$merged = $periodicEvents->merge($mappedEvents)->sortKeys();

		return $merged;
	}
}
