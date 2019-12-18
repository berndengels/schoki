<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\StartPage;
//use PHPUnit\Framework\TestResult;

class PageTest extends DuskTestCase
{
    /**
     * A basic browser test example.
     *
     * @return void
     */
    public function testStartPage()
    {
        $this->browse(function (Browser $browser) {
			$browser->visit(new StartPage());
		});
    }
}
