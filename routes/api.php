<?php

use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Http\Request;
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

Route::post('/', function(Request $request) {

    $type = $request->get('type');

    if ($type === 'register') {
        return (new RegisterController())->register($request);
    }

    throw new UnexpectedValueException("Unexpected request type: $type", 500);
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
