<?php
/**
 * FilePermissions.php
 *
 * @author    Bernd Engels
 * @created   16.05.19 18:10
 * @copyright Webwerk Berlin GmbH
 */

namespace App\Helper;

use App;

class FilePermissions {

	private static $writableDirs = [
		'bootstrap/cache',
		'storage/app',
		'storage/framework',
		'storage/framework/cache/data',
		'storage/framework/sessions',
		'storage/framework/testing',
		'storage/framework/views',
		'storage/logs',
		'public_html/thumbs',
		'public_html/uploads',
		'public_html/media/images',
		'public_html/media/audios',
		'public_html/media/videos',
	];

	public static function makeWritable()
	{
		$basePath = App::basePath();

		set_time_limit(0);
		ob_implicit_flush(true);
		ob_end_flush();
		echo "<h3>Set Permissions</h3><pre>";

		foreach(self::$writableDirs as $dir) {
			$fullPath = "$basePath/$dir/";
			echo "<h5>$fullPath</h5>";
			$output = system("chmod -R 777 $fullPath");
			echo "$dir: $output\n";
		}
		echo '</pre>';
	}
}