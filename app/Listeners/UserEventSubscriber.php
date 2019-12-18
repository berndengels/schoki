<?php
/**
 * UserEventSubscriber.php
 *
 * @author    Bernd Engels
 * @created   12.03.19 19:20
 * @copyright Webwerk Berlin GmbH
 */

namespace App\Listeners;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Events\Dispatcher;

class UserEventSubscriber
{
    /**
     * Handle user login events.
     */
    public function onUserLogin($event) {
		$event->user->setLastLogin()->save();
		return $event->user;
    }

    /**
     * Handle user logout events.
     */
    public function onUserLogout($event) {
        config()->set('session.driver', 'array');
        return $event->user;
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param  Dispatcher  $events
     */
    public function subscribe($events)
    {
        $events->listen(
            'Illuminate\Auth\Events\Login',
            'App\Listeners\UserEventSubscriber@onUserLogin'
        );

        $events->listen(
            'Illuminate\Auth\Events\Logout',
            'App\Listeners\UserEventSubscriber@onUserLogout'
        );
    }
}
