<?php

namespace App\Models;

//use App\Models\Role;
//use Spatie\Permission\Traits\HasRoles;
use Carbon\Carbon;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\DatabaseNotificationCollection;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Kyslik\ColumnSortable\Sortable;
use Laravel\Sanctum\HasApiTokens;

/**
 * App\Models\User
 *
 * @property int $id
 * @property int|null $is_super_admin
 * @property string $username
 * @property string $email
 * @property int|null $enabled
 * @property string $password
 * @property \Illuminate\Support\Carbon|null $last_login
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read Collection|EventTemplate[] $eventTemplatesCreated
 * @property-read int|null $event_templates_created_count
 * @property-read Collection|EventTemplate[] $eventTemplatesUpdated
 * @property-read int|null $event_templates_updated_count
 * @property-read Collection|Event[] $eventsCreated
 * @property-read int|null $events_created_count
 * @property-read Collection|EventPeriodic[] $eventsPeriodicCreated
 * @property-read int|null $events_periodic_created_count
 * @property-read Collection|EventPeriodic[] $eventsPeriodicUpdated
 * @property-read int|null $events_periodic_updated_count
 * @property-read Collection|Event[] $eventsUpdated
 * @property-read int|null $events_updated_count
 * @property-read Collection|MusicStyle[] $musicStyles
 * @property-read int|null $music_styles_count
 * @property-read Collection|Newsletter[] $newsletterCreated
 * @property-read int|null $newsletter_created_count
 * @property-read Collection|Newsletter[] $newsletterUpdated
 * @property-read int|null $newsletter_updated_count
 * @property-read DatabaseNotificationCollection|DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read Collection|Page[] $pagesCreated
 * @property-read int|null $pages_created_count
 * @property-read Collection|Page[] $pagesUpdated
 * @property-read int|null $pages_updated_count
 * @method static Builder|User newModelQuery()
 * @method static Builder|User newQuery()
 * @method static Builder|User query()
 * @method static Builder|User sortable($defaultParameters = null)
 * @method static Builder|User whereCreatedAt($value)
 * @method static Builder|User whereEmail($value)
 * @method static Builder|User whereEnabled($value)
 * @method static Builder|User whereId($value)
 * @method static Builder|User whereIsSuperAdmin($value)
 * @method static Builder|User whereLastLogin($value)
 * @method static Builder|User wherePassword($value)
 * @method static Builder|User whereRememberToken($value)
 * @method static Builder|User whereUpdatedAt($value)
 * @method static Builder|User whereUsername($value)
 * @mixin Eloquent
 */
class User extends Authenticatable
{
//    use Notifiable, HasRoles;
    use Notifiable, Sortable, HasApiTokens;
	public $sortable = [
		'username',
		'email',
		'last_login',
		'enabled',
	];

    /**
     * @var string
     */
    protected $table = 'my_user';
	protected $primaryKey = 'id';
	public $incrementing = true;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $dates = ['last_login','created_at','updated_at'];

	public function musicStyles()
	{
		return $this->belongsToMany(MusicStyle::class, 'user_music_styles', 'user_id', 'music_style_id');
	}

	public function setLastLogin()
	{
		$now = Carbon::now('Europe/berlin');
		$this->last_login = $now->format('Y-m-d H:i:s');
		return $this;
	}

	/**
	 * @return HasMany
	 */
	public function eventsCreated()
	{
		return $this->hasMany(Event::class, 'created_by', 'id');
	}

	public function eventsUpdated()
	{
		return $this->hasMany(Event::class, 'updated_by', 'id');
	}

	/**
	 * @return HasMany
	 */
	public function eventTemplatesCreated()
	{
		return $this->hasMany(EventTemplate::class, 'created_by', 'id');
	}

	public function eventTemplatesUpdated()
	{
		return $this->hasMany(EventTemplate::class, 'updated_by', 'id');
	}

	/**
	 * @return HasMany
	 */
	public function eventsPeriodicCreated()
	{
		return $this->hasMany(EventPeriodic::class, 'created_by', 'id');
	}

	/**
	 * @return HasMany
	 */
	public function eventsPeriodicUpdated()
	{
		return $this->hasMany(EventPeriodic::class, 'updated_by', 'id');
	}

	/**
	 * @return HasMany
	 */
	public function pagesCreated()
	{
		return $this->hasMany(Page::class, 'created_by', 'id');
	}

	/**
	 * @return HasMany
	 */
	public function pagesUpdated()
	{
		return $this->hasMany(Page::class, 'updated_by', 'id');
	}

	/**
	 * @return HasMany
	 */
	public function newsletterCreated()
	{
		return $this->hasMany(Newsletter::class, 'created_by', 'id');
	}

	/**
	 * @return HasMany
	 */
	public function newsletterUpdated()
	{
		return $this->hasMany(Newsletter::class, 'updated_by', 'id');
	}
}
