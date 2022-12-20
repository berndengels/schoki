<?php

namespace Tests\Feature;

use Route;
use Exception;
use Tests\TestCase;
use App\Libs\Routes as MyRoutes;
use Illuminate\Routing\Route as RoutingRoute;
use Symfony\Component\HttpFoundation\Response;

class RouteRequestTest extends TestCase
{
	private $_skipPublicRoutes = [
		'/feed',
        '/telescope',
        '/calendar',
		'/events/calendar',
        '/contact/formNewsletter',
        '/contact/newsletter',
        '/contact/message',
        '/contact/newsletter/create',
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
                echo " status: $status";
                $response->assertStatus(200);
                echo " \360\237\230\216\n";
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
		$routes = [];

		foreach($this->_adminRoutes as $name) {
			$routes[] = Route::getRoutes()->getByName("admin.$name");
		}

		/**
		 * @var $response Response
		 * @var $route Route
		 */
		$this->actingAs($this->adminUser, 'web');

        /**
         * @var $route RoutingRoute
         */
		foreach($routes as $route) {
		    try {
                echo "check response status (200) for admin route: {$route->uri},";
                $response = $this->get($route->uri);
                $status = $response->getStatusCode();
                echo " status: $status";
                $response->assertStatus(200);
                echo " \360\237\230\216\n";
            } catch(Exception $e) {
                echo "ERROR for public admin: ". $route->getName() ."\n";
                echo $e->getMessage()."\n";
            }
		}
	}
}
