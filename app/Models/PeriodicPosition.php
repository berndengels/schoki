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
 * App\Models\PeriodicPosition
 *
 * @property int $id
 * @property string $name
 * @property string $search_key
 * @property-read Collection|EventPeriodic[] $eventPeriodic
 * @property-read int|null $event_periodic_count
 * @method static Builder|PeriodicPosition newModelQuery()
 * @method static Builder|PeriodicPosition newQuery()
 * @method static Builder|PeriodicPosition query()
 * @method static Builder|PeriodicPosition whereId($value)
 * @method static Builder|PeriodicPosition whereName($value)
 * @method static Builder|PeriodicPosition whereSearchKey($value)
 * @mixin Eloquent
 */
class PeriodicPosition extends Model
{
    /**
     * @var string
     */
    protected $table = 'periodic_position';
    /**
     * @var array
     */
    protected $fillable = ['name', 'search_key'];

    /**
     * @return HasMany
     */
    public function eventPeriodic()
    {
        return $this->hasMany('App\Models\EventPeriodic');
    }


}
