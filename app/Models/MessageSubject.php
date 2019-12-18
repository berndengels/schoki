<?php

namespace App\Models;

use App\Models\MusicStyle;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\MessageSubject
 *
 * @property int $id
 * @property string $name
 * @property-read Collection|Message[] $messages
 * @property-read int|null $messages_count
 * @method static Builder|MessageSubject newModelQuery()
 * @method static Builder|MessageSubject newQuery()
 * @method static Builder|MessageSubject query()
 * @method static Builder|MessageSubject whereId($value)
 * @method static Builder|MessageSubject whereName($value)
 * @mixin Eloquent
 */
class MessageSubject extends Model
{
    protected $table = 'message_subject';
    protected $fillable = ['name'];
    public $timestamps = false;

	/**
	 * @return HasMany
	 */
	public function messages()
	{
		return $this->hasMany(Message::class);
	}

}
