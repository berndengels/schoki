<?php

namespace App\Models;

use Carbon\Carbon;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use Kyslik\ColumnSortable\Sortable;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Category
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string|null $icon
 * @property string|null $default_time
 * @property int|null $default_price
 * @property int $is_published
 * @property-read Collection<int, EventPeriodic> $eventPeriodics
 * @property-read int|null $event_periodics_count
 * @property-read Collection<int, EventTemplate> $eventTemplates
 * @property-read int|null $event_templates_count
 * @property-read Collection<int, Event> $events
 * @property-read int|null $events_count
 * @method static Builder|Category newModelQuery()
 * @method static Builder|Category newQuery()
 * @method static Builder|Category query()
 * @method static Builder|Category sortable($defaultParameters = null)
 * @method static Builder|Category whereDefaultPrice($value)
 * @method static Builder|Category whereDefaultTime($value)
 * @method static Builder|Category whereIcon($value)
 * @method static Builder|Category whereId($value)
 * @method static Builder|Category whereIsPublished($value)
 * @method static Builder|Category whereName($value)
 * @method static Builder|Category whereSlug($value)
 * @mixin Eloquent
 */
class Category extends Model
{
	use Sortable;

	protected $table = 'category';
    protected $fillable = [
        'name',
        'slug',
        'icon',
        'default_time',
        'default_price',
        'is_published',
    ];
    public $timestamps = false;
	public $sortable = [
		'name',
	];

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
}
