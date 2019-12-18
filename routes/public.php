<?php

use App\Helper\MyDate;
use App\Models\Event;
use App\Models\Theme;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

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
        Route::get("/static/$slug", 'StaticPageController@get')->name("public.static.$slug");
    });
}

Route::get('/feed', 'EventController@feed')->name('public.feed');
Route::get('/ical', 'EventController@ical')->name('public.ical');
Route::get('/remove/address/show/{token}', 'ContactController@removeAddressShow')->name('public.removeAddressShow');
Route::post('/remove/address/hard/{token}', 'ContactController@removeAddressHard')->name('public.removeAddressHard');
Route::get('/events', 'EventController@getActualMergedEvents')->name('public.events');
Route::get('/events/show/{date}', 'EventController@show')->name('public.event.eventsShow');
Route::get('/events/calendar', 'EventController@calendar')->name('public.eventCalendar');
//Route::post('/events/lazy/{date}', 'EventController@lazy')->name('public.eventLazy');
Route::post('/events/lazyByCategory/{category}/{date}', 'EventController@lazyByCategory')->name('public.eventLazyByCategory');
Route::post('/events/lazyByTheme/{theme}/{date}', 'EventController@lazyByTheme')->name('public.eventLazyByTheme');

$categories = Category::where('is_published', 1)->get();
foreach($categories as $item) {
	Route::get('/events/category/'.$item->slug, 'EventController@getActualMergedEventsByCategory')
		->name('public.eventsByCategory.' . $item->slug)
	;
}
$themes = Theme::where('is_published', 1)->get();
foreach($themes as $item) {
	Route::get('/events/theme/'.$item->slug, 'EventController@getActualMergedEventsByTheme')
		->name('public.eventsByTheme.'. $item->slug)
	;
}
Route::get('/page/{slug}', 'PageController@get')->name('public.page');
Route::permanentRedirect('/', '/events');

Route::prefix('contact')->group(function () {
    Route::get('/formBands', 'ContactController@formBands')->name('public.bandsForm');
    Route::post('/sendBands', 'ContactController@sendBands')->name('action.sendBands');
//    Route::get('/formNewsletter', 'ContactController@formNewsletter')->name('public.newsletterForm');
//	Route::post('/sendNewsletter', 'ContactController@sendNewsletter')->name('action.sendNewsletter');
    Route::get('/formNewsletter', 'ContactController@formNewsletterSubscribe')->name('public.formNewsletterSubscribe');
    Route::post('/sendNewsletter', 'ContactController@sendNewsletterSubscribe')->name('action.sendNewsletterSubscribe');
});

Route::get('/logout', function() {
    Auth::logout();
    Session::invalidate();
    return redirect()->route('public.events');
});

/*
Route::fallback(function () {
    return redirect()->route('public.events');
});
*/
