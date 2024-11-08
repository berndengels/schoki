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
use function PHPUnit\Framework\directoryExists;

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

	public function __construct()
	{
		set_time_limit(0);
	}

	public static function makeWritable()
	{
		$basePath = App::basePath();
		echo "<h3>Set Permissions on $basePath</h3>";

		$testPath = realpath("{$basePath}/../test.schokoladen-mitte.de/repo");
		echo "<h5>$testPath</h5>";
		ob_flush();
		flush();

		$output = system("find $testPath -type d -exec chmod 0777 {} +");
		echo "Dirs: $output<br>";
		ob_flush();
		flush();

		$output = system("find $testPath -type f -exec chmod 0666 {} +");
		echo "Files: $output<br>";
		ob_flush();
		flush();

		foreach(self::$writableDirs as $dir) {
			$fullPath = "$basePath/$dir/";
			echo "<h5>$fullPath</h5>";
			ob_flush();
			flush();

			$output = system("chmod -R 777 $fullPath");
			echo "$dir: $output<br>";
			ob_flush();
			flush();
		}
	}
}