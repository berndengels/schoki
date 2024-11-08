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
		$repoPath = realpath("/web/schokoladen-mitte.de/test.schokoladen-mitte.de/repo");
		$storagePath = realpath("/web/schokoladen-mitte.de/test.schokoladen-mitte.de/repo/storage");
		echo "<h5>$repoPath</h5>";
		ob_flush();
		flush();

		if(is_writable($storagePath)) {
			@chmod($storagePath, 0777);
		}

		$output = system("find $repoPath -type d -exec chmod 0777 {} +");
		echo "Dirs: $output<br>";
		ob_flush();
		flush();

		$output = system("find $repoPath -type f -exec chmod 0666 {} +");
		echo "Files: $output<br>";
		ob_flush();
		flush();

		$basePath = App::basePath();
		echo "<h3>Set Permissions on $basePath</h3>";

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