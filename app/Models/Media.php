<?php

namespace App\Models;

use App\Models\Ext\HasUser;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Media
 *
 * @property-read User $createdBy
 * @property-read Event $event
 * @property-read EventPeriodic $eventPeriodic
 * @property-read Page $page
 * @property-read User $updatedBy
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
		return $this->belongsTo('App\Models\Page');
	}

    public function event()
    {
        return $this->belongsTo('App\Models\Event');
    }

    public function eventPeriodic()
    {
        return $this->belongsTo('App\Models\EventPeriodic');
    }
}
