<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\dashboard\UserController;
use App\Http\Controllers\dashboard\PlacesController;
use App\Http\Controllers\dashboard\hotelController;
use App\Http\Controllers\dashboard\tourController;
use App\Http\Controllers\home\AuthController;
use App\Http\Controllers\home\homeTourController;
use App\Http\Controllers\home\ProfileController;
use App\Http\Controllers\home\homeHotelController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:api');
Route::post('/register', [AuthController::class, 'processRegistration']);
Route::post('/forgetPassword', [AuthController::class, 'forgetPassword']);
Route::get('/change-Password/{user}/{token}', [AuthController::class, 'showResetPasswordForm'])->name('resetPassword');
Route::post('/change-Password/{user}/{token}', [AuthController::class, 'processResetPassword'])->name('resetPassword.process');


Route::prefix('profile')->middleware('auth:api')->group(function () {
    Route::get('/', [ProfileController::class, 'profile']);
    // Route::post('/passwordChange', [ProfileController::class, 'passwordChange']);
    Route::post('/profileChange', [ProfileController::class, 'profileChange']);
});

// Route::get('/users/{id}', [UserController::class, 'index']);
Route::prefix('dashboard')->group(function () {
    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'index']);
        Route::get('/create', [UserController::class, 'create']);
        Route::post('/create', [UserController::class, 'store']);
        Route::get('/{id}/edit', [UserController::class, 'edit']);
        Route::post('/{id}', [UserController::class, 'update']);
    });

    Route::prefix('places')->group(function () {
        Route::get('/', [PlacesController::class, 'index']);
        Route::post('/create', [PlacesController::class, 'store']);
        Route::get('/{slug}/edit', [PlacesController::class, 'edit']);
        Route::post('/{slug}', [PlacesController::class, 'update']);
        Route::post('/delete/{id}', [PlacesController::class, 'delete']);
    });

    Route::prefix('Hotel')->group(function () {
        Route::get('/create', [hotelController::class, 'createHotel']);
        Route::post('/create', [hotelController::class, 'storeHotel']);

        Route::get('/widget/{name?}', [hotelController::class, 'getWidget']);
        Route::post('/widget/create', [hotelController::class, 'storeWidget']);
        Route::post('/widget/delete/{id}', [hotelController::class, 'deleteWidget']);

        Route::get('/{name?}', [hotelController::class, 'index']);
        Route::get('/{slug}/edit', [hotelController::class, 'editHotel']);
        Route::post('/{slug}/edit', [hotelController::class, 'updateHotel']);
        Route::post('/delete/{id}', [hotelController::class, 'deleteHotel']);

        Route::get('/{slug}/room', [hotelController::class, 'getRoom']);
        Route::get('/{slug}/room/create', [hotelController::class, 'getCreateRoom']);
        Route::post('/{slug}/room/create', [hotelController::class, 'storeRoom']);
        Route::get('/{slug}/room/{slugRoom}/edit', [hotelController::class, 'getEditRoom']);
        Route::post('/{slug}/room/{slugRoom}/edit', [hotelController::class, 'updateRoom']);
        Route::post('/{slug}/room/delete/{id}', [hotelController::class, 'deleteRoom']);
    });

    Route::prefix('tour')->group(function () {
        Route::get('/', [tourController::class, 'getTours']);
        Route::get('/create', [tourController::class, 'getCreateTour']);
        Route::post('/create', [tourController::class, 'storeTour']);
        Route::get('/{slug}/edit', [tourController::class, 'getEditTour']);
        Route::post('/{slug}/edit', [tourController::class, 'updateTour']);
        Route::post('/delete/{id}', [tourController::class, 'delete']);

        Route::get('/{slug}/time', [tourController::class, 'getTourtime']);
        Route::get('/{slug}/time/create', [tourController::class, 'getCreateTourTime']);
        Route::post('/{slug}/time/create', [tourController::class, 'storeTourTimes']);
        Route::get('/{slug}/time/{id}/edit', [tourController::class, 'getEditTourTime']);
        Route::post('/{slug}/time/{id}/edit', [tourController::class, 'updateTourTime']);
        Route::post('/{slug}/time/delete/{id}', [tourController::class, 'deleteTourTime']);
    });
});

Route::post('/upload', [hotelController::class, 'upload']);

Route::prefix('hotel')->group(function () {
    Route::get('/home', [homeHotelController::class, 'indexHotel']);
    Route::get('/search/{search?}', [homeHotelController::class, 'searchHotel']);
});

Route::get('/tour', [homeTourController::class, 'show']);
Route::get('/tour/{slug}', [homeTourController::class, 'tourdetail']);
Route::get('/tour/search/search-by-place/{country}', [homeTourController::class, 'searchByPlace']);
Route::get('/tour/search/{search?}', [homeTourController::class, 'searchTour']);

// Route::prefix('tour')->group(function () {
//     Route::get('/home', [homeHotelController::class, 'indexHotel']);
//     Route::get('/search/{search?}', [homeHotelController::class, 'searchHotel']);
// });
