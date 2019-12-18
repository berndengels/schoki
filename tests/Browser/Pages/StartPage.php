<?php

namespace Tests\Browser\Pages;

use Laravel\Dusk\Page;
use Laravel\Dusk\Browser;
use ReflectionClass;
use Tests\DuskTestCase;

class StartPage extends Page
{
    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url()
    {
        return '/';
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
			->waitFor('.mbs')
			->resize(1024, 768)
			->assertPathIs('/events')
			->assertSee('Events')
			->screenshot($screenName)
		;
		@chmod( DuskTestCase::getScreenPath().'/'.$screenName . '.png' );

		DuskTestCase::createJpeg($screenName);
	}

    /**
     * Get the element shortcuts for the page.
     *
     * @return array
     */
    public function elements()
    {
        return [
            '@element' => '.eventContainer',
        ];
    }
}
