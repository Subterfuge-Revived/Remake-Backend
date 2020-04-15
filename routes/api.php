<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\BlockController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\MessageGroupController;
use App\Http\Controllers\RoomController;
use App\Http\Middleware\AuthenticateAPI;
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

    // FIXME: This is not the proper way to enforce middleware.
    // Using different routes will resolve this problem.
    return app(AuthenticateAPI::class)->handle($request, function (Request $request) use ($type) {
        if ($type === 'new_room') {
            return (new RoomController())->store($request);
        }
        if ($type === 'join_room') {
            return (new RoomController())->join($request);
        }
        if ($type === 'leave_room') {
            return (new RoomController())->leave($request);
        }
        if ($type === 'start_early') {
            return (new RoomController())->startEarly($request);
        }
        if ($type === 'submit_event') {
            return (new EventController())->store($request);
        }
        if ($type === 'get_events') {
            return (new EventController())->index($request);
        }
        if ($type === 'cancel_event') {
            return (new EventController())->delete($request);
        }
        if ($type === 'get_room_data') {
            return (new RoomController())->index($request);
        }
        if ($type === 'create_group') {
            return (new MessageGroupController())->store($request);
        }
        if ($type === 'message') {
            return (new MessageController())->store($request);
        }
        if ($type === 'get_message') {
            return (new MessageController())->index($request);
        }
        if ($type === 'block') {
            return (new BlockController())->store($request);
        }
        if ($type === 'unblock') {
            return (new BlockController())->delete($request);
        }
        if ($type === 'get_blocks') {
            return (new BlockController())->index($request);
        }

        throw ValidationException::withMessages([
            "Unexpected request type: $type",
        ]);
    });
});

// RESTful API for rooms
Route::resource('/rooms', 'RoomController')->except(['create', 'edit']);
