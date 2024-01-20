<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\Audios
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
 * @property int $duration
 * @property int $bitrate
 * @property-read User|null $createdBy
 * @property-read Event|null $event
 * @property-read EventPeriodic|null $eventPeriodic
 * @property-read Page|null $page
 * @property-read User|null $updatedBy
 * @method static Builder|Audios newModelQuery()
 * @method static Builder|Audios newQuery()
 * @method static Builder|Audios query()
 * @method static Builder|Audios whereBitrate($value)
 * @method static Builder|Audios whereCreatedAt($value)
 * @method static Builder|Audios whereCreatedBy($value)
 * @method static Builder|Audios whereDuration($value)
 * @method static Builder|Audios whereEventId($value)
 * @method static Builder|Audios whereEventPeriodicId($value)
 * @method static Builder|Audios whereExtension($value)
 * @method static Builder|Audios whereExternalFilename($value)
 * @method static Builder|Audios whereFilesize($value)
 * @method static Builder|Audios whereId($value)
 * @method static Builder|Audios whereInternalFilename($value)
 * @method static Builder|Audios wherePageId($value)
 * @method static Builder|Audios whereTitle($value)
 * @method static Builder|Audios whereUpdatedAt($value)
 * @method static Builder|Audios whereUpdatedBy($value)
 * @mixin Eloquent
 */
class Audios extends Media
{
    protected $table = 'audios';

    /**
     * @return BelongsTo
     */
    public function page()
    {
        return $this->belongsTo(Page::class);
    }
}
