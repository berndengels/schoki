<?php

namespace Tests\Browser\Pages\Admin;

use App\Models\User;
use Laravel\Dusk\Page;
use Laravel\Dusk\Browser;
use ReflectionClass;
use Tests\DuskTestCase;

class AfterLoginPage extends Page
{
	/**
	 * @var User
	 */
	protected $user;

	public function __construct( User $user ) {
		$this->user = $user;
	}

	/**
	 * Get the URL for the page.
	 *
	 * @return string
	 */
	public function url()
	{
		return '/login';
	}

	/**
	 * Assert that the browser is on the page.
	 *
	 * @param  Browser  $browser
	 * @return void
	 */
	public function assert(Browser $browser)
	{
		$screenName	= lcfirst((new ReflectionClass($this))->getShortName());

		$browser
			->loginAs($this->user)
			->visit('/admin/events')
			->waitFor('.mbs')
			->resize(1024, 768)
			->assertPathIs('/admin/events')
			->screenshot($screenName)
		;
		@chmod( DuskTestCase::getScreenPath().'/'.$screenName . '.png', 0666 );
		DuskTestCase::createJpeg($screenName);
	}

    /**
     * Get the global element shortcuts for the site.
     *
     * @return array
     */
    public static function siteElements()
    {
        return [
            '@element' => 'table',
        ];
    }
}
