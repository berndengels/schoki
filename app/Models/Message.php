<?php

namespace App\Models;

use App\Traits\Models\Sanitize;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;
use Kyslik\ColumnSortable\Sortable;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Address
 *
 * @property int $id
 * @property int|null $music_style_id
 * @property string $email
 * @property string $name
 * @property string $message
 * @property Carbon $created_at
 * @property-read MessageSubject $messageSubject
 * @property-read MusicStyle|null $musicStyle
 * @method static Builder|Message newModelQuery()
 * @method static Builder|Message newQuery()
 * @method static Builder|Message query()
 * @method static Builder|Message sortable($defaultParameters = null)
 * @method static Builder|Message whereCreatedAt($value)
 * @method static Builder|Message whereEmail($value)
 * @method static Builder|Message whereId($value)
 * @method static Builder|Message whereMessage($value)
 * @method static Builder|Message whereMusicStyleId($value)
 * @method static Builder|Message whereName($value)
 * @mixin Eloquent
 * @property string $msg
 * @method static Builder|Message whereMsg($value)
 */
class Message extends Model
{
	use Sortable, Sanitize;

    protected $table = 'message';
    protected $guarded = ['id'];

	const UPDATED_AT = null;

	public $sortable = [
		'musicStyle',
		'name',
		'email',
		'created_at',
	];

    public function setMsgAttribute($value)
    {
        $this->attributes['msg'] = $this->sanitize($value);
    }

	public function musicStyle()
	{
		return $this->belongsTo(MusicStyle::class);
	}
}
