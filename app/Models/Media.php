<?php

namespace App\Models;

use Eloquent;
use App\Models\Ext\HasUser;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Media
 *
 * @property-read User|null $createdBy
 * @property-read Event|null $event
 * @property-read EventPeriodic|null $eventPeriodic
 * @property-read Page|null $page
 * @property-read User|null $updatedBy
 * @method static Builder|Media newModelQuery()
 * @method static Builder|Media newQuery()
 * @method static Builder|Media query()
 * @mixin Eloquent
 */
class Media extends Model
{
	use HasUser;

    protected $fillable = ['title'];

	public function page()
	{
		return $this->belongsTo(Page::class);
	}

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function eventPeriodic()
    {
        return $this->belongsTo(EventPeriodic::class);
    }
}
