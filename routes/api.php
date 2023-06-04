<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PlaceController;

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



// User Authentication
Route::post('/signup', [UserController::class, 'signup']);
Route::post('/login', [UserController::class, 'login']);

// Nearby Places
Route::middleware('auth:api')->group(function () {
    Route::get('/places', [PlaceController::class, 'getNearbyPlaces']);
});


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});