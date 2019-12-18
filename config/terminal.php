<?php

use App\Console\Commands\DuskCommand;
use Recca0120\Terminal\Console\Commands\ArtisanTinker;
use Recca0120\Terminal\Console\Commands\Cleanup;
use Recca0120\Terminal\Console\Commands\Composer;
use Recca0120\Terminal\Console\Commands\Find;
use Recca0120\Terminal\Console\Commands\Mysql;
use Recca0120\Terminal\Console\Commands\Tail;
use Recca0120\Terminal\Console\Commands\Vi;

return [

    /*
    |--------------------------------------------------------------------------
    | Package Enabled
    |--------------------------------------------------------------------------
    |
    | This value determines whether the package is enabled. By default it
    | will be enabled if APP_DEBUG is true.
    |
    */
    'enabled' => env('WEB_TERMINAL'),

    /*
    |--------------------------------------------------------------------------
    | Whitelisted IP Addresses
    |--------------------------------------------------------------------------
    |
    | This value contains a list of IP addresses that are allowed to access
    | the Laravel terminal.
    |
    */
    'whitelists' => [
		'127.0.0.1',
		'85.214.25.31',
		'83.137.101.114',
	],

    /*
    |--------------------------------------------------------------------------
    | Route Configuration
    |--------------------------------------------------------------------------
    |
    | This value sets the route information such as the prefix and middleware.
    |
    */
    'route' => [
		'middleware'	=> ['web','auth'],
        'prefix'		=> 'terminal',
        'as'			=> 'terminal.',
    ],

    /*
    |--------------------------------------------------------------------------
    | Enabled Commands
    |--------------------------------------------------------------------------
    |
    | This value contains a list of class names for the available commands
    | for Laravel Terminal.
    |
    */
    'commands' => [
        \Recca0120\Terminal\Console\Commands\Artisan::class,
        ArtisanTinker::class,
        Cleanup::class,
        Composer::class,
        Find::class,
        Mysql::class,
        Tail::class,
        Vi::class,
		DuskCommand::class,
    ],
];
