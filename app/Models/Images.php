<?php

namespace App\Models;

use Eloquent;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;

/**
 * App\Models\Images
 *
 * @property int $id
 * @property int|null $event_id
 * @property int|null $page_id
 * @property int|null $event_periodic_id
 * @property int|null $event_template_id
 * @property string $external_filename
 * @property string $internal_filename
 * @property string|null $title
 * @property string $extension
 * @property int $filesize
 * @property int|null $updated_by
 * @property int $created_by
 * @property Carbon $created_at
 * @property Carbon|null $updated_at
 * @property int|null $eventPeriodic_id
 * @property int|null $width
 * @property int|null $height
 * @property-read User|null $createdBy
 * @property-read Event|null $event
 * @property-read EventPeriodic|null $eventPeriodic
 * @property-read int $display_height
 * @property-read int $display_width
 * @property-read Page|null $page
 * @property-read User|null $updatedBy
 * @method static Builder|Images newModelQuery()
 * @method static Builder|Images newQuery()
 * @method static Builder|Images query()
 * @method static Builder|Images whereCreatedAt($value)
 * @method static Builder|Images whereCreatedBy($value)
 * @method static Builder|Images whereEventId($value)
 * @method static Builder|Images whereEventPeriodicId($value)
 * @method static Builder|Images whereEventTemplateId($value)
 * @method static Builder|Images whereExtension($value)
 * @method static Builder|Images whereExternalFilename($value)
 * @method static Builder|Images whereFilesize($value)
 * @method static Builder|Images whereHeight($value)
 * @method static Builder|Images whereId($value)
 * @method static Builder|Images whereInternalFilename($value)
 * @method static Builder|Images wherePageId($value)
 * @method static Builder|Images whereTitle($value)
 * @method static Builder|Images whereUpdatedAt($value)
 * @method static Builder|Images whereUpdatedBy($value)
 * @method static Builder|Images whereWidth($value)
 * @mixin Eloquent
 */
class Images extends Media
{
    /**
     * @var string
     */
    protected $table = 'images';
    /**
     * @var string[]
     */
    protected $casts = [
        'width'     => 'integer',
        'height'    => 'integer',
        'filesize'  => 'integer',
    ];
    /**
     * @var string[]
     */
    protected $appends = ['displayWidth', 'displayHeight'];

    /**
     * @return int
     */
    public function getDisplayWidthAttribute()
    {
		if($this->height > 0) {
			return round(config('event.maxImageHeight') * $this->width / $this->height);
		}
		return null;
    }

    /**
     * @return int
     */
    public function getDisplayHeightAttribute()
    {
        return config('event.maxImageHeight');
    }
}
