<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\RoomController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;

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

Route::post('/', function (Request $request) {

    // FIXME: These should all be different routes and not all registered to the document root.
    $type = $request->get('type');

    if ($type === 'register') {
        return (new RegisterController())->register($request);
    }
    if ($type === 'login') {
        return (new LoginController())->login($request);
    }
    if ($type === 'new_room') {
        return (new RoomController())->store($request);
    }
    if ($type === 'join_room') {
        return (new RoomController())->join($request);
    }
    if ($type === 'leave_room') {
        return (new RoomController())->leave($request);
    }

    throw ValidationException::withMessages([
        "Unexpected request type: $type",
    ]);
});

// RESTful API for rooms
Route::resource('/rooms', 'RoomController');
