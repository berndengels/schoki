<?php
/**
 * CacheController.php
 *
 * @author    Bernd Engels
 * @created   05.05.19 16:09
 * @copyright Webwerk Berlin GmbH
 */

namespace App\Http\Controllers\Admin;

use Exception;
use Illuminate\Support\Facades\Cache;

class CacheController extends MainController
{

	public function flush()
	{
		$success	= false;
		$error		= null;
		try {
			$success = Cache::flush();
		} catch(Exception $e) {
			$error = $e->getMessage();
		}
		return view('admin.cache', [
			'success'	=> $success,
			'error'		=> $error,
			'action' 	=> str_replace('()','', __FUNCTION__)
		]);
	}

	public function clear()
	{
		$success	= false;
		$error		= null;
		try {
			$success = Cache::clear();
		} catch(Exception $e) {
			$error = $e->getMessage();
		}
		return view('admin.cache', [
			'success'	=> $success,
			'error'		=> $error,
			'action' 	=> str_replace('()','', __FUNCTION__)
		]);
	}

	public function forget($key)
	{
		$success	= false;
		$error		= null;
		try {
			$success = Cache::forget($key);
		} catch(Exception $e) {
			$error = $e->getMessage();
		}
		return view('admin.cache', [
			'success'	=> $success,
			'error'		=> $error,
			'action'	=> str_replace('()','', __FUNCTION__) . ': ' . $key
		]);
	}
}
