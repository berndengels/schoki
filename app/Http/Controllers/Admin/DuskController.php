<?php
/**
 * DuskController.php
 *
 * @author    Bernd Engels
 * @created   18.05.19 17:31
 * @copyright Webwerk Berlin GmbH
 */

namespace App\Http\Controllers\Admin;

use Artisan;
use Exception;

//use Tests\DuskTestCase;

class DuskController extends ServiceController {

	public function runBrowserTest() {
		set_time_limit(0);
		ob_implicit_flush(true);
		ob_end_flush();

		try {
			Artisan::call('dusk:run');
			$output = Artisan::output();
		} catch(Exception $e) {
			$output = $e->getMessage()."\n".$e->getTraceAsString();
		}
		return view('artisan-console.interface', [
			'title'		=> 'Running Browser Test',
			'command'	=> 'dusk:run',
		]);
	}
}
