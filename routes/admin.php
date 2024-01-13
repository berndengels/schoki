<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes();
Auth::routes(['register' => false]);

Route::prefix('admin')->group(function () {
    Route::post('/file/upload', 'Admin\FileController@upload')->name('admin.fileUpload');
	Route::post('/file/uploadAudio', 'Admin\FileController@uploadAudio')->name('admin.fileUploadAudio');
    Route::post('/file/editorUpload', 'Admin\FileController@editorUpload')->name('admin.fileEditorUpload');
    Route::post('/file/uploadCropped', 'Admin\FileController@uploadCropped')->name('admin.fileUploadCropped');
    Route::post('/file/delete', 'Admin\FileController@delete')->name('admin.fileDelete');
    Route::post('/file/deleteDropfile', 'Admin\FileController@deleteDropfile')->name('admin.dropfileDelete');
    Route::get('/file/syncImages', 'Admin\FileController@syncImages')->name('admin.syncImages');

    Route::get('/events', 'Admin\EventController@listEvents')->name('admin.eventList');
	Route::get('/events/archive', 'Admin\EventController@archive')->name('admin.eventArchive');
	Route::get('/events/archive/search', 'Admin\EventController@archive')->name('admin.eventArchiveSearch');
    Route::get('/events/edit', 'Admin\EventController@edit')->name('admin.eventNew');
    Route::get('/events/edit/{id?}', 'Admin\EventController@edit')->name('admin.eventEdit');
    Route::get('/events/delete/{id}', 'Admin\EventController@delete')->name('admin.eventDelete');
	Route::post('/events/template', 'Admin\EventController@template')->name('admin.eventTemplateSelect');
	Route::post('/events/store/{id?}', 'Admin\EventController@store')->name('admin.eventStore');
	Route::post('/events/override/{id?}', 'Admin\EventController@override')->name('admin.eventOverride');
    Route::post('/events/checkForPeriodicDate', 'Admin\EventController@checkForPeriodicDate')->name('admin.checkForPeriodicDate');

	Route::get('/eventsTemplate', 'Admin\EventTemplateController@index')->name('admin.eventTemplateList');
	Route::get('/eventsTemplate/edit', 'Admin\EventTemplateController@edit')->name('admin.eventTemplateNew');
	Route::get('/eventsTemplate/edit/{id?}', 'Admin\EventTemplateController@edit')->name('admin.eventTemplateEdit');
	Route::get('/eventsTemplate/delete/{id}', 'Admin\EventTemplateController@delete')->name('admin.eventTemplateDelete');
	Route::post('/eventsTemplate/store/{id?}', 'Admin\EventTemplateController@store')->name('admin.eventTemplateStore');

	Route::get('/eventsPeriodic', 'Admin\EventPeriodicController@index')->name('admin.eventPeriodicList');
    Route::get('/eventsPeriodic/edit', 'Admin\EventPeriodicController@edit')->name('admin.eventPeriodicNew');
    Route::get('/eventsPeriodic/edit/{id?}', 'Admin\EventPeriodicController@edit')->name('admin.eventPeriodicEdit');
    Route::get('/eventsPeriodic/delete/{id}', 'Admin\EventPeriodicController@delete')->name('admin.eventPeriodicDelete');
    Route::post('/eventsPeriodic/store/{id?}', 'Admin\EventPeriodicController@store')->name('admin.eventPeriodicStore');

    Route::get('/users', 'Admin\UserController@index')->name('admin.userList');
    Route::get('/users/edit', 'Admin\UserController@edit')->name('admin.userNew');
    Route::get('/users/edit/{id?}', 'Admin\UserController@edit')->name('admin.userEdit');
    Route::get('/users/delete/{id}', 'Admin\UserController@delete')->name('admin.userDelete');
    Route::post('/users/store/{id?}', 'Admin\UserController@store')->name('admin.userStore');
	Route::get('/users/reset', 'Admin\UserController@reset')->name('admin.userReset');

    Route::get('/categories', 'Admin\CategoryController@index')->name('admin.categoryList');
    Route::get('/categories/edit', 'Admin\CategoryController@edit')->name('admin.categoryNew');
    Route::get('/categories/edit/{id?}', 'Admin\CategoryController@edit')->name('admin.categoryEdit');
    Route::get('/categories/delete/{id}', 'Admin\CategoryController@delete')->name('admin.categoryDelete');
    Route::post('/categories/store/{id?}', 'Admin\CategoryController@store')->name('admin.categoryStore');

    Route::get('/themes', 'Admin\ThemeController@index')->name('admin.themeList');
    Route::get('/themes/edit', 'Admin\ThemeController@edit')->name('admin.themeNew');
    Route::get('/themes/edit/{id?}', 'Admin\ThemeController@edit')->name('admin.themeEdit');
    Route::get('/themes/delete/{id}', 'Admin\ThemeController@delete')->name('admin.themeDelete');
    Route::post('/themes/store/{id?}', 'Admin\ThemeController@store')->name('admin.themeStore');

    Route::get('/pages', 'Admin\PageController@index')->name('admin.pageList');
    Route::get('/pages/edit', 'Admin\PageController@edit')->name('admin.pageNew');
    Route::get('/pages/edit/{id?}', 'Admin\PageController@edit')->name('admin.pageEdit');
    Route::get('/pages/delete/{id}', 'Admin\PageController@delete')->name('admin.pageDelete');
    Route::post('/pages/store/{id?}', 'Admin\PageController@store')->name('admin.pageStore');

	Route::get('/musicStyles', 'Admin\MusicStyleController@index')->name('admin.musicStyleList');
	Route::get('/musicStyles/edit', 'Admin\MusicStyleController@edit')->name('admin.musicStyleNew');
	Route::get('/musicStyles/edit/{id?}', 'Admin\MusicStyleController@edit')->name('admin.musicStyleEdit');
	Route::get('/musicStyles/delete/{id}', 'Admin\MusicStyleController@delete')->name('admin.musicStyleDelete');
	Route::post('/musicStyles/store/{id?}', 'Admin\MusicStyleController@store')->name('admin.musicStyleStore');

	Route::get('/addresses', 'Admin\AddressController@listAll')->name('admin.addressList');
	Route::get('/addresses/show/{id}', 'Admin\AddressController@show')->name('admin.addressShow');
	Route::get('/addresses/edit', 'Admin\AddressController@edit')->name('admin.addressNew');
	Route::get('/addresses/edit/{id?}', 'Admin\AddressController@edit')->name('admin.addressEdit');
	Route::get('/addresses/delete/{id}', 'Admin\AddressController@delete')->name('admin.addressDelete');
	Route::post('/addresses/store/{id?}', 'Admin\AddressController@store')->name('admin.addressStore');

	Route::get('/addressCategories', 'Admin\AddressCategoryController@index')->name('admin.addressCategoryList');
//	Route::get('/addressCategories/show/{id}', 'Admin\AddressCategoryController@show')->name('admin.addressCategoryShow');
	Route::get('/addressCategories/edit', 'Admin\AddressCategoryController@edit')->name('admin.addressCategoryNew');
	Route::get('/addressCategories/edit/{id?}', 'Admin\AddressCategoryController@edit')->name('admin.addressCategoryEdit');
	Route::get('/addressCategories/delete/{id}', 'Admin\AddressCategoryController@delete')->name('admin.addressCategoryDelete');
	Route::post('/addressCategories/store/{id?}', 'Admin\AddressCategoryController@store')->name('admin.addressCategoryStore');

	Route::get('/newsletter', 'Admin\NewsletterController@index')->name('admin.newsletterList');
	Route::get('/newsletter/edit/{campaignId?}', 'Admin\NewsletterController@edit')->name('admin.newsletterEdit');
	Route::get('/newsletter/delete/{campaignId}', 'Admin\NewsletterController@delete')->name('admin.newsletterDelete');
	Route::post('/newsletter/process', 'Admin\NewsletterController@process')->name('admin.newsletterProcess');
	Route::post('/newsletter/preview', 'Admin\NewsletterController@preview')->name('admin.newsletterPreview');
	Route::post('/newsletter/create', 'Admin\NewsletterController@create')->name('admin.newsletterCreate');
	Route::post('/newsletter/check', 'Admin\NewsletterController@check')->name('admin.newsletterCheck');
	Route::post('/newsletter/test', 'Admin\NewsletterController@test')->name('admin.newsletterTest');
	Route::post('/newsletter/send', 'Admin\NewsletterController@send')->name('admin.newsletterSend');
	Route::get('/newsletter/members/{segmentId}', 'Admin\NewsletterController@getMembers')->name('admin.newsletterMembers');

	Route::get('/messages', 'Admin\MessageController@getList')->name('admin.messageList');
	Route::get('/messages/show/{id}', 'Admin\MessageController@show')->name('admin.messageShow');
	Route::get('/messages/edit', 'Admin\MessageController@edit')->name('admin.messageNew');
	Route::get('/messages/edit/{id?}', 'Admin\MessageController@edit')->name('admin.messageEdit');
	Route::get('/messages/delete/{id}', 'Admin\MessageController@delete')->name('admin.messageDelete');
	Route::post('/messages/store/{id?}', 'Admin\MessageController@store')->name('admin.messageStore');

    Route::get('/services/dumpdb', 'Admin\ServiceController@dumpDb')->name('admin.service.dumpDb');
	Route::get('/services/syncImages', 'Admin\ServiceController@syncImages')->name('admin.service.syncImages');
	Route::get('/services/makeThumbs', 'Admin\ServiceController@makeThumbs')->name('admin.service.makeThumbs');
    Route::get('/services/sanitizeImageDB', 'Admin\ServiceController@sanitizeImageDB')->name('admin.service.sanitizeImageDB');
    Route::get('/services/cropImages', 'Admin\ServiceController@cropImages')->name('admin.service.cropImages');
	Route::get('/services/syncAudios', 'Admin\ServiceController@syncAudios')->name('admin.service.syncAudios');
	Route::get('/services/sanitizeFilePermissions', 'Admin\ServiceController@sanitizeFilePermissions')->name('admin.service.sanitizeFilePermissions');
	Route::get('/services/browserTestReport', 'Admin\ServiceController@browserTestReport')->name('admin.service.browserTestReport');
	Route::get('/dusk/runBrowserTest', 'Admin\DuskController@runBrowserTest')->name('admin.dusk.runBrowserTest');
//	Route::get('/terminal/{view?}', 'Admin\TerminalController@index')->name('admin.terminal.index');
//	Route::get('/terminal/endpoint', 'Admin\TerminalController@endpoint')->name('admin.terminal.endpoint');
//	Route::get('/terminal/media', 'Admin\TerminalController@media')->name('admin.terminal.media');
	Route::get('/cache/flush', 'Admin\CacheController@flush')->name('admin.service.cacheFlush');
	Route::get('/cache/clear', 'Admin\CacheController@clear')->name('admin.service.cacheClear');
    Route::get('/cache/forget/{key}', 'Admin\CacheController@forget')->name('admin.service.cacheForget');
	Route::get('/cache/phpinfo', function () {
		return view('admin.phpinfo');
	})->name('admin.phpinfo');

	Route::group([
		'prefix' => 'menus',
	], function () {
		Route::get('show', 'Admin\MenuController@show')->name('admin.menuShow');
		Route::get('edit', 'Admin\MenuController@edit')->name('admin.menuNew');
		Route::post('operation/{operation}', 'Admin\MenuController@operation')->name('admin.menuOperation');
		Route::post('store', 'Admin\MenuController@store')->name('admin.menuStore');
		Route::get('icons', 'Admin\MenuController@icons')->name('admin.menuIcons');
	});
    Route::middleware('auth')->get('phpinfo', fn() => phpinfo());
});
