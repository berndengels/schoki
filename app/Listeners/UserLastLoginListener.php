<?php
/**
 * UserListener.php
 *
 * @author    Bernd Engels
 * @created   12.03.19 18:22
 * @copyright Webwerk Berlin GmbH
 */
namespace App\Listener;

use App\Models\User;
use App\Events\LoginLogoutEvent;
use Carbon\Carbon;
use \Illuminate\Auth\Events\Authenticated;

class UserLastLoginListener
{
/*
	public function handle(LoginLogoutEvent $event)
	{
		$user	= $event->getUser();
		$now	= Carbon::now('Europe/Berlin');
		$user->update(['last_login'=>$now->format('Y-m-d H:m:i')]);
	}
*/
}