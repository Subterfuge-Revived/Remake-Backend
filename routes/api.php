<?php

use Illuminate\Support\Facades\Route;

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

Route::post('/register', 'Auth\RegisterController@register');
Route::post('/login', 'Auth\LoginController@login');

Route::group(['middleware' => 'auth.api'], function () {
    Route::resource('rooms', 'RoomController')->except(['create', 'edit']);
    Route::resource('blocks', 'BlockController')->except(['create', 'edit']);
    Route::resource('events', 'EventController')->except(['create', 'edit']);

    // Next up: message group controller to get a list of messages etc.
    Route::resource('groups', 'MessageGroupController')->except(['create', 'edit']);
    Route::resource('groups.messages', 'MessageController')->except(['create', 'edit']);

});
