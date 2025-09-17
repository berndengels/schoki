<?php

use App\Models\Theme;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes();
Auth::routes(['register' => false]);
Route::get('/logout', function() {
	Auth::logout();
	session()->invalidate();

	return redirect()->route('public.events');
});

Route::permanentRedirect('/events', '/');
Route::permanentRedirect('/intern', '/admin/events');
Route::permanentRedirect('/admin', '/admin/events');

$staticPages = collect([]);
$pathStaticPages = collect(config('view.paths'))->map(function($path) use (&$staticPages) {
	$path = $path . '/public/static';
	if(is_dir($path)) {
		foreach(scandir($path) as $item) {
			if(false !== strrpos($item, '.blade.php')) {
				$staticPages = $staticPages->merge(basename($item,'.blade.php'));
			}
		}
	}
});
if($staticPages->count() > 0)  {
	$staticPages->each(function($slug){
		Route::middleware('web')->get("/static/$slug", 'StaticPageController@get')->name("public.static.$slug");
	});
}

Route::group(['middleware' => 'web'], function () {
//	Route::get('/feed', 'EventController@feed')->name('public.feed');
	Route::get('/ical', 'EventController@ical')->name('public.ical');
	Route::get('/', 'EventController@getActualMergedEvents')->name('public.events');
	Route::get('show/{date}', 'EventController@show')->name('public.event.eventsShow');
	//Route::get('calendar/{year}/{month}', 'EventController@calendar')->name('public.eventCalendar');
	Route::get('calendar', 'EventController@calendar')->name('public.eventCalendar');
});

$categories = Category::where('is_published', 1)->get();
foreach($categories as $item) {
	Route::middleware('web')->get('/events/category/'.$item->slug, 'EventController@getActualMergedEventsByCategory')->name('public.eventsByCategory.' . $item->slug);
}
$themes = Theme::where('is_published', 1)->get();
foreach($themes as $item) {
	Route::middleware('web')->get('/theme/'.$item->slug, 'EventController@getActualMergedEventsByTheme')->name('public.eventsByTheme.'. $item->slug);
}
Route::get('/page/{slug}', 'PageController@get')->name('public.page');

Route::group([
	'prefix'     => 'contact',
	'as'         => 'public.',
	'middleware' => 'web'
], function () {
	Route::resource('message', 'BandMessageController')->only(['create','store']);
	Route::resource('newsletter', 'NewsletterController')->only(['create','store']);
});

// admin sites
Route::group([
	'prefix'	=> 'admin',
	'as'	=> 'admin.',
	'middleware'	=> ['auth'],
], function () {
    Route::post('/file/upload', 'Admin\FileController@upload')->name('fileUpload');
	Route::post('/file/uploadAudio', 'Admin\FileController@uploadAudio')->name('fileUploadAudio');
    Route::post('/file/editorUpload', 'Admin\FileController@editorUpload')->name('fileEditorUpload');
    Route::post('/file/uploadCropped', 'Admin\FileController@uploadCropped')->name('fileUploadCropped');
    Route::post('/file/delete', 'Admin\FileController@delete')->name('fileDelete');
    Route::post('/file/deleteDropfile', 'Admin\FileController@deleteDropfile')->name('dropfileDelete');
    Route::get('/file/syncImages', 'Admin\FileController@syncImages')->name('syncImages');

    Route::get('/events', 'Admin\EventController@listEvents')->name('eventList');
	Route::get('/events/archive', 'Admin\EventController@archive')->name('eventArchive');
	Route::get('/events/archive/search', 'Admin\EventController@archive')->name('eventArchiveSearch');
    Route::get('/events/edit', 'Admin\EventController@edit')->name('eventNew');
    Route::get('/events/edit/{id?}', 'Admin\EventController@edit')->name('eventEdit');
    Route::get('/events/delete/{id}', 'Admin\EventController@delete')->name('eventDelete');
	Route::post('/events/template', 'Admin\EventController@template')->name('eventTemplateSelect');
	Route::post('/events/store/{id?}', 'Admin\EventController@store')->name('eventStore');
	Route::post('/events/override/{id?}', 'Admin\EventController@override')->name('eventOverride');
    Route::post('/events/checkForPeriodicDate', 'Admin\EventController@checkForPeriodicDate')->name('checkForPeriodicDate');

	Route::get('/eventsTemplate', 'Admin\EventTemplateController@index')->name('eventTemplateList');
	Route::get('/eventsTemplate/edit', 'Admin\EventTemplateController@edit')->name('eventTemplateNew');
	Route::get('/eventsTemplate/edit/{id?}', 'Admin\EventTemplateController@edit')->name('eventTemplateEdit');
	Route::get('/eventsTemplate/delete/{id}', 'Admin\EventTemplateController@delete')->name('eventTemplateDelete');
	Route::post('/eventsTemplate/store/{id?}', 'Admin\EventTemplateController@store')->name('eventTemplateStore');

	Route::get('/eventsPeriodic', 'Admin\EventPeriodicController@index')->name('eventPeriodicList');
    Route::get('/eventsPeriodic/edit', 'Admin\EventPeriodicController@edit')->name('eventPeriodicNew');
    Route::get('/eventsPeriodic/edit/{id?}', 'Admin\EventPeriodicController@edit')->name('eventPeriodicEdit');
    Route::get('/eventsPeriodic/delete/{id}', 'Admin\EventPeriodicController@delete')->name('eventPeriodicDelete');
    Route::post('/eventsPeriodic/store/{id?}', 'Admin\EventPeriodicController@store')->name('eventPeriodicStore');

    Route::get('/users', 'Admin\UserController@index')->name('userList');
    Route::get('/users/edit', 'Admin\UserController@edit')->name('userNew');
    Route::get('/users/edit/{id?}', 'Admin\UserController@edit')->name('userEdit');
    Route::get('/users/delete/{id}', 'Admin\UserController@delete')->name('userDelete');
    Route::post('/users/store/{id?}', 'Admin\UserController@store')->name('userStore');
	Route::get('/users/reset', 'Admin\UserController@reset')->name('userReset');

    Route::get('/categories', 'Admin\CategoryController@index')->name('categoryList');
    Route::get('/categories/edit', 'Admin\CategoryController@edit')->name('categoryNew');
    Route::get('/categories/edit/{id?}', 'Admin\CategoryController@edit')->name('categoryEdit');
    Route::get('/categories/delete/{id}', 'Admin\CategoryController@delete')->name('categoryDelete');
    Route::post('/categories/store/{id?}', 'Admin\CategoryController@store')->name('categoryStore');

    Route::get('/themes', 'Admin\ThemeController@index')->name('themeList');
    Route::get('/themes/edit', 'Admin\ThemeController@edit')->name('themeNew');
    Route::get('/themes/edit/{id?}', 'Admin\ThemeController@edit')->name('themeEdit');
    Route::get('/themes/delete/{id}', 'Admin\ThemeController@delete')->name('themeDelete');
    Route::post('/themes/store/{id?}', 'Admin\ThemeController@store')->name('themeStore');

    Route::get('/pages', 'Admin\PageController@index')->name('pageList');
    Route::get('/pages/edit', 'Admin\PageController@edit')->name('pageNew');
    Route::get('/pages/edit/{id?}', 'Admin\PageController@edit')->name('pageEdit');
    Route::get('/pages/delete/{id}', 'Admin\PageController@delete')->name('pageDelete');
    Route::post('/pages/store/{id?}', 'Admin\PageController@store')->name('pageStore');

	Route::get('/musicStyles', 'Admin\MusicStyleController@index')->name('musicStyleList');
	Route::get('/musicStyles/edit', 'Admin\MusicStyleController@edit')->name('musicStyleNew');
	Route::get('/musicStyles/edit/{id?}', 'Admin\MusicStyleController@edit')->name('musicStyleEdit');
	Route::get('/musicStyles/delete/{id}', 'Admin\MusicStyleController@delete')->name('musicStyleDelete');
	Route::post('/musicStyles/store/{id?}', 'Admin\MusicStyleController@store')->name('musicStyleStore');

	Route::get('/addresses', 'Admin\AddressController@listAll')->name('addressList');
	Route::get('/addresses/show/{id}', 'Admin\AddressController@show')->name('addressShow');
	Route::get('/addresses/edit', 'Admin\AddressController@edit')->name('addressNew');
	Route::get('/addresses/edit/{id?}', 'Admin\AddressController@edit')->name('addressEdit');
	Route::get('/addresses/delete/{id}', 'Admin\AddressController@delete')->name('addressDelete');
	Route::post('/addresses/store/{id?}', 'Admin\AddressController@store')->name('addressStore');

	Route::get('/addressCategories', 'Admin\AddressCategoryController@index')->name('addressCategoryList');
//	Route::get('/addressCategories/show/{id}', 'Admin\AddressCategoryController@show')->name('addressCategoryShow');
	Route::get('/addressCategories/edit', 'Admin\AddressCategoryController@edit')->name('addressCategoryNew');
	Route::get('/addressCategories/edit/{id?}', 'Admin\AddressCategoryController@edit')->name('addressCategoryEdit');
	Route::get('/addressCategories/delete/{id}', 'Admin\AddressCategoryController@delete')->name('addressCategoryDelete');
	Route::post('/addressCategories/store/{id?}', 'Admin\AddressCategoryController@store')->name('addressCategoryStore');

	Route::get('/newsletter', 'Admin\NewsletterController@index')->name('newsletterList');
	Route::get('/newsletter/edit/{campaignId?}', 'Admin\NewsletterController@edit')->name('newsletterEdit');
	Route::get('/newsletter/delete/{campaignId}', 'Admin\NewsletterController@delete')->name('newsletterDelete');
	Route::post('/newsletter/process', 'Admin\NewsletterController@process')->name('newsletterProcess');
	Route::post('/newsletter/preview', 'Admin\NewsletterController@preview')->name('newsletterPreview');
	Route::post('/newsletter/create', 'Admin\NewsletterController@create')->name('newsletterCreate');
	Route::post('/newsletter/check', 'Admin\NewsletterController@check')->name('newsletterCheck');
	Route::post('/newsletter/test', 'Admin\NewsletterController@test')->name('newsletterTest');
	Route::post('/newsletter/send', 'Admin\NewsletterController@send')->name('newsletterSend');
	Route::get('/newsletter/members/{segmentId}', 'Admin\NewsletterController@getMembers')->name('newsletterMembers');

	Route::get('/messages', 'Admin\MessageController@getList')->name('messageList');
	Route::get('/messages/show/{id}', 'Admin\MessageController@show')->name('messageShow');
	Route::get('/messages/edit', 'Admin\MessageController@edit')->name('messageNew');
	Route::get('/messages/edit/{id?}', 'Admin\MessageController@edit')->name('messageEdit');
	Route::get('/messages/delete/{id}', 'Admin\MessageController@delete')->name('messageDelete');
	Route::post('/messages/store/{id?}', 'Admin\MessageController@store')->name('messageStore');

    Route::get('/services/dumpdb', 'Admin\ServiceController@dumpDb')->name('service.dumpDb');
	Route::get('/services/syncImages', 'Admin\ServiceController@syncImages')->name('service.syncImages');
	Route::get('/services/makeThumbs', 'Admin\ServiceController@makeThumbs')->name('service.makeThumbs');
    Route::get('/services/sanitizeImageDB', 'Admin\ServiceController@sanitizeImageDB')->name('service.sanitizeImageDB');
    Route::get('/services/cropImages', 'Admin\ServiceController@cropImages')->name('service.cropImages');
	Route::get('/services/syncAudios', 'Admin\ServiceController@syncAudios')->name('service.syncAudios');
	Route::get('/services/sanitizeFilePermissions', 'Admin\ServiceController@sanitizeFilePermissions')->name('service.sanitizeFilePermissions');
	Route::get('/services/browserTestReport', 'Admin\ServiceController@browserTestReport')->name('service.browserTestReport');
	Route::get('/dusk/runBrowserTest', 'Admin\DuskController@runBrowserTest')->name('dusk.runBrowserTest');
//	Route::get('/terminal/{view?}', 'Admin\TerminalController@index')->name('terminal.index');
//	Route::get('/terminal/endpoint', 'Admin\TerminalController@endpoint')->name('terminal.endpoint');
//	Route::get('/terminal/media', 'Admin\TerminalController@media')->name('terminal.media');
	Route::get('/cache/flush', 'Admin\CacheController@flush')->name('service.cacheFlush');
	Route::get('/cache/clear', 'Admin\CacheController@clear')->name('service.cacheClear');
    Route::get('/cache/forget/{key}', 'Admin\CacheController@forget')->name('service.cacheForget');
	Route::get('/phpinfo','Admin\ServiceController@phpinfo' )->name('phpinfo');
/*
	Route::get('/git_pull', function() {
		echo '<pre>';
		putenv( 'COMPOSER_ALLOW_XDEBUG=1' );
		putenv( 'COMPOSER_DISABLE_XDEBUG_WARN=1' );
		$npm = getenv('NPM');
		$npmCmd = "$npm run prod";
		echo 'Pull new changes:' . PHP_EOL;
		echo shell_exec( 'git pull 2>&1' );
		echo $npmCmd . PHP_EOL;
		echo shell_exec( $npmCmd ) . PHP_EOL;
		echo '</pre>';
	})->name('git_pull');
*/
	Route::group([
		'prefix' => 'menus',
	], function () {
		Route::get('show', 'Admin\MenuController@show')->name('menuShow');
		Route::get('edit', 'Admin\MenuController@edit')->name('menuNew');
		Route::post('operation/{operation}', 'Admin\MenuController@operation')->name('menuOperation');
		Route::post('store', 'Admin\MenuController@store')->name('menuStore');
		Route::get('icons', 'Admin\MenuController@icons')->name('menuIcons');
	});
});

Route::fallback(fn() => redirect()->route('public.events'));
?>