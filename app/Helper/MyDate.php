<?php
/**
 * MyDate.php
 *
 * @author    Bernd Engels
 * @created   11.04.19 15:19
 * @copyright Webwerk Berlin GmbH
 */

namespace App\Helper;

use Carbon\Carbon;

/**
 * Class MyDate
 */
class MyDate {

	/**
	 * @var int
	 */
	static protected $offsetHours = 5;
	/**
	 * @var string
	 */
	static protected $tz = 'Europe/berlin';

	/**
	 * @return Carbon
	 */
	public static function getToday() {
		return Carbon::now(static::$tz);
	}

	/**
	 * @return Carbon
	 */
	public static function getUntilValidDate() {
		return static::getToday()->copy()->subHours(static::$offsetHours);
	}
}
