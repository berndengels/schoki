<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

/**
 * App\Models\Videos
 *
 * @property int $id
 * @property int|null $event_id
 * @property int|null $page_id
 * @property int|null $event_periodic_id
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
 * @property int $duration
 * @property int $bitrate
 * @property-read User|null $createdBy
 * @property-read Event|null $event
 * @property-read EventPeriodic|null $eventPeriodic
 * @property-read Page|null $page
 * @property-read User|null $updatedBy
 * @method static Builder|Videos newModelQuery()
 * @method static Builder|Videos newQuery()
 * @method static Builder|Videos query()
 * @method static Builder|Videos whereBitrate($value)
 * @method static Builder|Videos whereCreatedAt($value)
 * @method static Builder|Videos whereCreatedBy($value)
 * @method static Builder|Videos whereDuration($value)
 * @method static Builder|Videos whereEventId($value)
 * @method static Builder|Videos whereEventPeriodicId($value)
 * @method static Builder|Videos whereExtension($value)
 * @method static Builder|Videos whereExternalFilename($value)
 * @method static Builder|Videos whereFilesize($value)
 * @method static Builder|Videos whereHeight($value)
 * @method static Builder|Videos whereId($value)
 * @method static Builder|Videos whereInternalFilename($value)
 * @method static Builder|Videos wherePageId($value)
 * @method static Builder|Videos whereTitle($value)
 * @method static Builder|Videos whereUpdatedAt($value)
 * @method static Builder|Videos whereUpdatedBy($value)
 * @method static Builder|Videos whereWidth($value)
 * @mixin Eloquent
 */
class Videos extends Media
{
    protected $table = 'videos';
}
