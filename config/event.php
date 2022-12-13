<?php
/**
 * event.php
 *
 * @author    Bernd Engels
 * @created   28.02.19 17:45
 * @copyright Webwerk Berlin GmbH
 */
return [
    'useCache'   => env('USE_CACHE', false),
    'defaultEventTime'  => env('DEFAULT_EVENT_TIME', '19:00'),
    'maxImageHeight'    => (int) env('MAX_IMAGE_HEIGHT', 300),
    'maxImageWidth'     => (int) env('MAX_IMAGE_WIDTH', 600),
	'maxImageFileSize'  => (int) env('MAX_IMAGE_FILESIZE', 60000),
	'paginationLimit' 	=> 10,
	'eventsPaginationLimit'	=> 10,
    'periodicPosition'  => [
        1           => 'jede Woche',
        2           => 'jeden zweiten',
        3           => 'jeden dritten',
        4           => 'jeden vierten',
        'first'     => 'monatlich jeden ersten',
        'second'    => 'monatlich jeden zweiten',
        'third'     => 'monatlich jeden dritten',
        'last'      => 'monatlich jeden letzten',
    ],
    'periodicWeekday'   => [
        'monday'        => 'Montag',
        'tuesday'       => 'Dienstag',
        'wednesday'     => 'Mittwoch',
        'thursday'      => 'Donnerstag',
        'friday'        => 'Freitag',
        'saturday'      => 'Samstag',
        'sunday'        => 'Sonntag',
    ],
    'position'   => [
        'lat'   => (float) env('LOCATION_LAT', null),
        'lng'   => (float) env('LOCATION_LNG', null),
    ],
    'ical'  => [
        'name'  => env('ICAL_NAME', null),
        'description'  => env('ICAL_DESCRIPTION', null),
        'location'  => env('ICAL_LOCATION', null),
    ]
];
