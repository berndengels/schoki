<?php
/**
 * Userable.php
 *
 * @author    Bernd Engels
 * @created   12.03.19 17:27
 * @copyright Webwerk Berlin GmbH
 */

namespace App\Models\Ext;

use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
		if(auth()->check()) {
            $user = auth()->user();
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
