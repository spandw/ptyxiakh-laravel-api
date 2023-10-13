<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiUserController;
use App\Http\Controllers\ApiLoginController;
use App\Http\Controllers\ApiParkingController;
use App\Http\Controllers\ApiReservationsController;

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

Route::post('/register', [ApiLoginController::class, 'register']);
Route::post('/login', [ApiLoginController::class, 'authenticate']);

// Route::get('/reservations', [ApiReservationsController::class, 'index']);




Route::group(['middleware' => ['auth:sanctum']], function () {



    Route::get('/check', [ApiReservationsController::class, 'checkAvailability']);
    Route::get('/check/{id}', [ApiReservationsController::class, 'getSpotReservationsDates']);
    Route::post('/create-reservation', [ApiReservationsController::class, 'createReservation']);

    Route::post('/create-spot', [ApiParkingController::class, 'store']);
    Route::post('/update-spot/{id}', [ApiParkingController::class, 'update']);
    Route::delete('/delete-spot/{id}', [ApiParkingController::class, 'destroy']);
    Route::get('/cities', [ApiParkingController::class, 'getDistinctCities']);
    Route::get('/parking-spots', [ApiParkingController::class, 'getFilteredParkingSpots']);
    Route::get('/parking-spots/{id}', [ApiUserController::class, 'getUserParkingSpots']);
    Route::get('/parking-spot/{id}', [ApiParkingController::class, 'getParkingSpotById']);

    Route::get('/user/{id}', [ApiUserController::class, 'getUserById']);
    Route::get('/users', [ApiUserController::class, 'getAllUsers']);
    Route::put('/edit-user', [ApiUserController::class, 'update']);
    Route::get('/user-reservations', [ApiUserController::class, 'getUserReservations']);


    Route::get('/user', [ApiLoginController::class, 'getCurrentUser']);
    Route::post('/logout', [ApiLoginController::class, 'logout']);
});
