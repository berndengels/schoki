<?php
/**
 * LoginLogout.php
 *
 * @author    Bernd Engels
 * @created   12.03.19 19:16
 * @copyright Webwerk Berlin GmbH
 */

namespace App\Events;

use App\Models\User;

/**
 * Class LoginLogout
 */
class LoginLogoutEvent
{
    /**
     * @var $user User|null
     */
    protected $user = null;

    /**
     * LoginLogout constructor.
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

	/**
	 * @return User|null
	 */
	public function getUser() {
		return $this->user;
	}
}