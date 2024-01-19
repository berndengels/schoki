<?php

use Illuminate\Support\Facades\Request;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
/*
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
*/
//Route::post('register', 'AuthController@register');
Route::post('login', 'ApiAuthController@login');
//Route::apiResource('events', 'ApiEventController');

Route::group([
	'prefix' => null,
], function () {
	Route::get('events/{date?}', 'ApiEventController@events');
    Route::get('event/{id}', 'ApiEventController@event');
    Route::get('eventDescriptionByDate/{date}', 'ApiEventController@eventDescriptionByDate');
});
Route::group([
    'prefix'    => 'spa',
    'namespace' => 'SPA'
], function () {
    Route::get('events/{date?}', 'SpaEventController@events');
    Route::get('events/category/{slug}', 'SpaEventController@eventsByCategory');
    Route::get('events/theme/{slug}', 'SpaEventController@eventsByTheme');
    Route::get('menu/tree', 'SpaMenuController@tree');
    Route::get('menu/top', 'SpaMenuController@top');
    Route::get('menu/bottom', 'SpaMenuController@bottom');
    Route::get('pages', 'SpaPageController@pages');
    Route::get('page/routes', 'SpaPageController@routes');
    Route::get('page/{slug}', 'SpaPageController@page');
    Route::get('musicStyles', 'SpaMusicStyleController@all');
    Route::get('contact/bands/fields', 'SpaContactController@fields');
    Route::post('contact/bands/send', 'SpaContactController@send');
});
?>