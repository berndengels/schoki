<?php

namespace Tests\Browser;

use Exception;
use Psy\Readline\Hoa\Console;
use Psy\Readline\Hoa\ConsoleOutput;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use App\Libs\Routes as MyRoutes;

class RouteTest extends DuskTestCase
{
	private $_skipRoutes = [
        '/feed',
        '/telescope',
        '/calendar',
        '/events/calendar',
        '/contact/formNewsletter',
        '/contact/newsletter',
        '/contact/message',
        '/contact/newsletter/create',
        '/contact/message/create',
	];

    /**
     * A basic browser test example.
     *
     * @return void
     */
    public function testPublicRoutes()
    {
		$routes = MyRoutes::getPublicRoutes()->reject(function ($value) {
            if (in_array($value, $this->_skipRoutes)) {
                echo "reject route: $value\n";
                return true;
            }
            return false;
		});
//        dump($routes);
		$this->browse(function (Browser $browser) use ($routes) {
			foreach($routes as $route) {
				$screenName = str_replace('/','-', trim($route, '/'));
                try {
                    echo "browser check (find NOT .exception DIV) to: $route\n";
                    $browser
                        ->visit($route)
                        ->resize(1024, 768)
                        ->assertMissing('.exception')
                        ->waitFor('.mbs')
                        ->screenshot($screenName)
                    ;
                    @chmod(parent::getScreenPath().'/'.$screenName . '.png', 0666);
                    parent::createJpeg($screenName);
                    sleep(1);
                } catch(Exception $e) {
                    echo "ERROR: browser check (find NOT .exception DIV) to: $route\n";
                }
			}
        });
    }
}
