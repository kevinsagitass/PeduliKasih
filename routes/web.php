<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::group(['middleware' => ['auth']], function () {

    #Home
    Route::get('/home', 'HomeController@index')->name('home');

    #Search
    Route::any('/search', 'HomeController@searchEvent');
    Route::any('/search-managed', 'HomeController@searchManagedEvent');
    Route::any('/search-joined', 'HomeController@searchJoinedEvent');

    #Promotor Managed Events
    Route::get('show-managed-events', 'HomeController@showManagedEvents')->name('show-managed-events');

    #Add Event
    Route::get('add-event', 'HomeController@showAddEventForm')->name('add-event');
    Route::post('add-event', 'EventController@addEvent');

    #User Joined Events
    Route::get('show-joined-events', 'HomeController@showJoinedEvents')->name('show-joined-events');

    #Event Details
    Route::get('event-details/{eventId}', 'EventController@eventDetails')->where('eventId', '[0-9]+');

    #User Join Event
    Route::post('join-event/{eventId}/user-id/{userId}', 'EventController@joinEvent')->where('eventId', '[0-9]+')->where('userId', '[0-9]+');

    #User UnJoin Event
    Route::post('unjoin-event/{eventId}/user-id/{userId}', 'EventController@unJoinEvent')->where('eventId', '[0-9]+')->where('userId', '[0-9]+');

    #Delete Event
    Route::post('delete-event/{eventId}', 'EventController@deleteEvent')->where('eventId', '[0-9]+');

    #Update Event
    Route::get('update-event/{eventId}', 'EventController@showUpdateEventForm')->where('eventId', '[0-9]+');
    Route::post('update-event/{eventId}', 'EventController@updateEvent')->where('eventId', '[0-9]+');
});
