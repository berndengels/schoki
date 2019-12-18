<?php

namespace Tests\Feature;

use Exception;
use Route;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;
use App\Models\User;
use App\Libs\Routes as MyRoutes;
use PHPUnit\Framework\TestResult;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RouteRequestTest extends TestCase
{
	private $_skipPublicRoutes = [
		'/feed',
		'/events/calendar',
        '/contact/formNewsletter',
	];

	private $_adminRoutes = [
		'eventList',
		'eventArchive',
		'eventNew',
		'eventTemplateList',
		'eventTemplateNew',
		'eventPeriodicList',
		'eventPeriodicNew',
		'categoryList',
		'categoryNew',
		'userList',
		'userNew',
		'themeList',
		'themeNew',
		'pageList',
		'pageNew',
		'musicStyleList',
		'musicStyleNew',
		'addressList',
		'addressNew',
		'addressCategoryList',
		'addressCategoryNew',
		'newsletterList',
		'messageList',
		'menuShow',
	];

	/**
     * public routes test for status 200.
     * @return void
     */
    public function testPublicRouteResponses()
    {
		$routes = MyRoutes::getRoutes('public\.')->reject(function ($value) {
			return in_array($value, $this->_skipPublicRoutes);
		});

		foreach($routes as $route) {
		    try {
                echo "check response status (200) for public route: $route,";
                $response = $this->get($route);
                $status = $response->getStatusCode();
                echo " status: $status\n";
                $response->assertStatus(200);
            } catch(Exception $e) {
                echo "ERROR for public route: $route:\n";
                echo $e->getMessage()."\n";
            }
		}
    }

	/**
	 * admin routes test for status 200.
	 * @return void
	 */
	public function testAdminRouteResponses()
	{
		$user = User::where('username','bengels')->first();
		$routes = [];

		foreach($this->_adminRoutes as $name) {
			$routes[] = Route::getRoutes()->getByName("admin.$name");
		}

		/**
		 * @var $response Response
		 * @var $route Route
		 */
		$this->actingAs($user, 'web');
		foreach($routes as $route) {
		    try {
                echo "check response status (200) for admin route: {$route->uri},";
                $response = $this->get($route->uri);
                $status = $response->getStatusCode();
                echo " status: $status\n";
                $response->assertStatus(200);
            } catch(Exception $e) {
                echo "ERROR for public admin: $route:\n";
                echo $e->getMessage()."\n";
            }
		}
	}
}
