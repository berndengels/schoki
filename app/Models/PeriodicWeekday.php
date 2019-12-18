<?php
/**
 * PeriodicPosition.php
 *
 * @author    Bernd Engels
 * @created   13.03.19 16:25
 * @copyright Webwerk Berlin GmbH
 */

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\PeriodicWeekday
 *
 * @property int $id
 * @property string $name_de
 * @property string $name_en
 * @property string $short
 * @property-read Collection|EventPeriodic[] $eventPeriodic
 * @property-read int|null $event_periodic_count
 * @method static Builder|PeriodicWeekday newModelQuery()
 * @method static Builder|PeriodicWeekday newQuery()
 * @method static Builder|PeriodicWeekday query()
 * @method static Builder|PeriodicWeekday whereId($value)
 * @method static Builder|PeriodicWeekday whereNameDe($value)
 * @method static Builder|PeriodicWeekday whereNameEn($value)
 * @method static Builder|PeriodicWeekday whereShort($value)
 * @mixin Eloquent
 */
class PeriodicWeekday extends Model
{
    /**
     * @var string
     */
    protected $table = 'periodic_weekday';
    /**
     * @var array
     */
    protected $fillable = ['name_de', 'name_en', 'short'];

    /**
     * @return HasMany
     */
    public function eventPeriodic()
    {
        return $this->hasMany('App\Models\EventPeriodic');
    }
}
