<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ButtonController;
use App\Http\Controllers\WebPageController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::group(['prefix'=>'auth'], function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);

});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('verify', [AuthController::class, 'verify']);
    Route::post('buttons', [ButtonController::class, 'store']);
    Route::get('buttons', [ButtonController::class, 'index']);
    Route::get('buttons/analytics', [ButtonController::class, 'analytics']);
    Route::post('buttons/click', [ButtonController::class, 'clicked']);
    Route::post('buttons/improve', [ButtonController::class, 'improve']);
    Route::post('web-pages/visit', [WebPageController::class, 'visit']);
});
