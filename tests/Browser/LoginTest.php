<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Admin\AfterLoginPage;

class LoginTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testLogin()
    {
		$adminUser = $this->getAdminUser();
		$this->browse(function (Browser $browser) use ($adminUser) {
			$browser->visit(new AfterLoginPage($adminUser));
		});
    }
}
