<?php
/**
 * Ionicons.php
 *
 * @author    Bernd Engels
 * @created   04.05.19 15:37
 * @copyright Webwerk Berlin GmbH
 */

namespace App\Libs;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

class Icons {
	/**
	 *
	 * @var Singleton
	 */
	private static $instance;
	private static $iconPath;
	private static $filePath;
	private static $fileWebPath;
	private static $cacheTTL = 3600 * 24; // 1 day

	public function __construct() {
	}

	public static function getInstance()
	{
		if ( is_null( self::$instance ) )
		{
			self::$instance = new self();
		}
		$basePath = app()->basePath();
        self::$iconPath = $basePath . '/node_modules/ionicons/dist/ionicons/svg';
		self::$filePath = $basePath . '/public_html/img/icons';
		self::$fileWebPath = '/img/icons';

		return self::$instance;
	}

	public static function getList()
	{
		/**
		 * @var $icons Collection
		 */
		$icons = collect([]);
		$self = self::getInstance();
		$webPath = self::$fileWebPath;

		foreach( scandir(self::$filePath) as $file ) {
			if(preg_match("/\.png$|\.gif$|\.jp(e)?g$|\.ico(n)?$|\.svg$/i", $file)) {
				if(false !== strrpos($file,'.svg')) {
					$tag = file_get_contents(self::$filePath.'/'.$file);
				} else {
					$tag = "<img class='icn' name='$file' src='$webPath/$file' alt='$file' title='$file'>";
				}

				$data = [
					'icon'	=> $file,
					'type'	=> 'file',
					'tag'	=> $tag,
				];
				$icons->add($data);
			}
		}

		foreach( scandir($self::$iconPath) as $icon) {
			if( false === strpos($icon,'ios-') && false !== strrpos($icon,'.svg') ) {
//					$icon = preg_replace('/^md\-/', '', basename($icon,'.svg'));
				$icon = basename($icon,'.svg');
				$data = [
					'icon'	=> $icon,
					'type'	=> 'ionicon',
//						'tag'	=> "<ion-icon class='icn' name='$icon' title='$file'></ion-icon>",
					'tag'	=> "<i class='icn ion-$icon' name='$icon' title='$icon'></i>",
				];
				$icons->add($data);
			}
		}

		return $icons;
	}

	public static function getCachedList()
	{
		if( false === Cache::has('icons') ) {
			$icons = self::getList();
			Cache::store('memcached')->put('icons', $icons, self::$cacheTTL);
		} else {
			$icons = Cache::get('icons', function () {
				$icons = self::getList();
				Cache::store('memcached')->put('icons', $icons, self::$cacheTTL);
			});
		}
		return $icons;
	}
}
