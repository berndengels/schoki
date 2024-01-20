<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

/**
 * App\Models\Theme
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string|null $default_time
 * @property int|null $default_price
 * @property string|null $icon
 * @property string|null $icon_orig
 * @property int|null $is_published
 * @property-read Collection<int, EventPeriodic> $eventPeriodics
 * @property-read int|null $event_periodics_count
 * @property-read Collection<int, EventTemplate> $eventTemplates
 * @property-read int|null $event_templates_count
 * @property-read Collection<int, Event> $events
 * @property-read int|null $events_count
 * @method static Builder|Theme newModelQuery()
 * @method static Builder|Theme newQuery()
 * @method static Builder|Theme query()
 * @method static Builder|Theme sortable($defaultParameters = null)
 * @method static Builder|Theme whereDefaultPrice($value)
 * @method static Builder|Theme whereDefaultTime($value)
 * @method static Builder|Theme whereIcon($value)
 * @method static Builder|Theme whereIconOrig($value)
 * @method static Builder|Theme whereId($value)
 * @method static Builder|Theme whereIsPublished($value)
 * @method static Builder|Theme whereName($value)
 * @method static Builder|Theme whereSlug($value)
 * @mixin Eloquent
 */
class Theme extends Model
{
	use Sortable;

	public $sortable = [
		'name',
	];
	protected $table = 'theme';
    protected $fillable = [
        'name',
        'slug',
        'icon',
        'icon_orig',
        'default_time',
        'default_price',
        'is_published',
    ];

    public $timestamps = false;

	public static function boot() {
		parent::boot();
		self::creating(function($model) {
			$model->slug = Str::slug(str_replace('.','_',$model->name), '-', 'de');
		});
		self::saving(function($model) {
			$model->slug = Str::slug(str_replace('.','_',$model->name), '-', 'de');
		});
		self::updating(function($model) {
			$model->slug = Str::slug(str_replace('.','_',$model->name), '-', 'de');
		});
	}

	/**
	 * @return HasMany
	 */
	public function events()
	{
		return $this->hasMany(Event::class);
	}

	/**
	 * @return HasMany
	 */
	public function eventTemplates()
	{
		return $this->hasMany(EventTemplate::class);
	}

    /**
     * @return HasMany
     */
    public function eventPeriodics()
    {
        return $this->hasMany(EventPeriodic::class);
    }

    public function delete()
    {
        $count = $this->events->count();
        if($count > 0) {
            return back()
                ->with('error','Es existieren noch Events ('.$count.') mit dieser Kategorie! Bitte vorher löschen.');
        }
        $count = $this->eventTemplates->count();
        if($count > 0) {
            return back()
                ->with('error','Es existieren noch Event Templates ('.$count.') mit dieser Kategorie! Bitte vorher löschen.');
        }
        $count = $this->eventPeriodics->count();
        if($count > 0) {
            return back()
                ->with('error','Es existieren noch periodische Event ('.$count.') mit dieser Kategorie! Bitte vorher löschen.');
        }
        return parent::delete();
    }
}
