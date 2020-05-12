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

Route::post('/register', 'Auth\RegisterController@register')->name('register');
Route::post('/login', 'Auth\LoginController@login')->name('login');

Route::group(['middleware' => 'auth.api'], function () {

    Route::resource('blocks', 'BlockController')->except(['create', 'edit']);

    Route::resource('goals', 'GoalController')->except(['create', 'edit']);

    Route::resource('rooms.groups', 'MessageGroupController')->except(['create', 'edit']);
    Route::resource('rooms.groups.messages', 'MessageController')->except(['create', 'edit']);

    Route::resource('rooms', 'RoomController')->except(['create', 'edit']);
    Route::resource('rooms.events', 'EventController')->except(['create', 'edit']);

    Route::post('rooms/{room}/join', 'RoomController@join')->name('rooms.join');
    Route::post('rooms/{room}/leave', 'RoomController@leave')->name('rooms.leave');
    Route::post('rooms/{room}/start', 'RoomController@startEarly')->name('rooms.start');

});
