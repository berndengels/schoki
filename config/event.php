<?php
/**
 * event.php
 *
 * @author    Bernd Engels
 * @created   28.02.19 17:45
 * @copyright Webwerk Berlin GmbH
 */
return [
    'defaultEventTime'  => env('DEFAULT_EVENT_TIME', '19:00'),
    'maxImageHeight'    => env('MAX_IMAGE_HEIGHT', 300),
    'maxImageWidth'     => env('MAX_IMAGE_WIDTH', 533),
	'maxImageFileSize'  => env('MAX_IMAGE_FILESIZE', 3), // MB
	'paginationLimit' 	=> 10,
	'eventsPaginationLimit'	=> 30,
];
