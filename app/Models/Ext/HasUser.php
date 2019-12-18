<?php
/**
 * Userable.php
 *
 * @author    Bernd Engels
 * @created   12.03.19 17:27
 * @copyright Webwerk Berlin GmbH
 */

namespace App\Models\Ext;

use Auth;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Event;

/**
 * Trait HasUser
 */
trait HasUser
{
	public static function bootHasUser()
	{
		/**
		 * @var $user User
		 */
		$user = auth()->user();
		if($user) {
			static::creating(function($table) use ($user)  {
				$table->created_by = $user->id;
			});
			static::saving(function($table) use ($user) {
				if( $table->id > 0 ) {
					$table->updated_by = $user->id;
				} else {
					$table->created_by = $user->id;
				}
			});
		}
	}

	/**
	 * @return BelongsTo
	 */
	public function createdBy()
	{
		return $this->belongsTo('App\Models\User', 'created_by', 'id');
	}

	/**
	 * @return BelongsTo
	 */
	public function updatedBy()
	{
		return $this->belongsTo('App\Models\User', 'updated_by', 'id');
	}
}
