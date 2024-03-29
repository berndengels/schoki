<?php
namespace App\Models;

use App\Models\Message;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Kyslik\ColumnSortable\Sortable;

/**
 * App\Models\MusicStyle
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property-read Collection<int, Message> $messages
 * @property-read int|null $messages_count
 * @property-read Collection<int, User> $users
 * @property-read int|null $users_count
 * @method static Builder|MusicStyle newModelQuery()
 * @method static Builder|MusicStyle newQuery()
 * @method static Builder|MusicStyle query()
 * @method static Builder|MusicStyle sortable($defaultParameters = null)
 * @method static Builder|MusicStyle whereId($value)
 * @method static Builder|MusicStyle whereName($value)
 * @method static Builder|MusicStyle whereSlug($value)
 * @mixin Eloquent
 */
class MusicStyle extends Model
{
	use Sortable;

	protected $table = 'music_style';
    protected $fillable = ['name'];
    public $timestamps = false;
	public $sortable = [
		'name',
	];

	public static function boot() {
		parent::boot();
		self::creating(function($model) {
			$model->slug = Str::slug(str_replace('.','_',$model->name), '-', 'de');
		});
		self::saving(function($model) {
			$model->slug = Str::slug(str_replace('.','_',$model->name), '-', 'de');
		});
		self::updating(function($model) {
			$model->slug = Str::slug(str_replace('.','_',$model->name), '-', 'de');
		});
	}
/*
	public function users()
	{
		return $this->belongsToMany(User::class, 'user_music_styles','user_id');
	}
*/
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'user_music_styles'
        );
    }

	/**
     * @return HasMany
     */
    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function delete()
    {
        $count = $this->users->count();
        if($count > 0) {
            return back()
                ->with('error','Es existieren noch User ('.$count.') mit dieser Kategorie! Bitte vorher löschen.');
        }
        $count = $this->messages->count();
        if($count > 0) {
            return back()
                ->with('error','Es existieren noch Nachrichtens ('.$count.') mit dieser Kategorie! Bitte vorher löschen.');
        }
        return parent::delete();
    }
}
