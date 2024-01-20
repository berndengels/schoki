<?php

namespace App\Models\Mocks;

use App\Models\Audios;
use App\Models\Category;
use App\Models\Image;
use App\Models\Event;
use App\Models\Images;
use App\Models\Theme;
use App\Models\User;
use App\Models\Videos;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;

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
 * @property string|null $ticketlink
 * @property Carbon $event_date
 * @property string|null $event_time
 * @property int|null $price
 * @property Carbon $created_at
 * @property Carbon|null $updated_at
 * @property bool|null $is_periodic
 * @property bool|null $is_published
 * @property-read Collection<int, Audios> $audios
 * @property-read int|null $audios_count
 * @property-read Category|null $category
 * @property-read User|null $createdBy
 * @property-read mixed $description_sanitized
 * @property-read mixed $description_text
 * @property-read mixed $description_without_video
 * @property-read mixed $event_link
 * @property-read Collection<int, Images> $images
 * @property-read int|null $images_count
 * @property-read Theme|null $theme
 * @property-read User|null $updatedBy
 * @property-read Collection<int, Videos> $videos
 * @property-read int|null $videos_count
 * @method static Builder|Event allActual()
 * @method static Builder|Event byCategorySlug($slug, $sinceToday = true)
 * @method static Builder|Event byThemeSlug($slug, $sinceToday = true)
 * @method static Builder|Event mergedByCategorySlug($slug, $sinceToday = true)
 * @method static Builder|Event mergedByDate($date)
 * @method static Builder|Event mergedByDateAndCategory($date, $slug)
 * @method static Builder|Event mergedByDateAndTheme($date, $slug)
 * @method static Builder|Event mergedByThemeSlug($slug, $sinceToday = true)
 * @method static Builder|EventMock newModelQuery()
 * @method static Builder|EventMock newQuery()
 * @method static Builder|EventMock query()
 * @method static Builder|Event sortable($defaultParameters = null)
 * @method static Builder|EventMock whereCategoryId($value)
 * @method static Builder|EventMock whereCreatedAt($value)
 * @method static Builder|EventMock whereCreatedBy($value)
 * @method static Builder|EventMock whereDescription($value)
 * @method static Builder|EventMock whereEventDate($value)
 * @method static Builder|EventMock whereEventTime($value)
 * @method static Builder|EventMock whereId($value)
 * @method static Builder|EventMock whereIsPeriodic($value)
 * @method static Builder|EventMock whereIsPublished($value)
 * @method static Builder|EventMock whereLinks($value)
 * @method static Builder|EventMock wherePrice($value)
 * @method static Builder|EventMock whereSubtitle($value)
 * @method static Builder|EventMock whereThemeId($value)
 * @method static Builder|EventMock whereTicketlink($value)
 * @method static Builder|EventMock whereTitle($value)
 * @method static Builder|EventMock whereUpdatedAt($value)
 * @method static Builder|EventMock whereUpdatedBy($value)
 * @mixin Eloquent
 */
class EventMock extends Event
{
}
