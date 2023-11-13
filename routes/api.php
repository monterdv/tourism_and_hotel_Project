<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\dashboard\UserController;
use App\Http\Controllers\dashboard\PlacesController;
use App\Http\Controllers\dashboard\hotelController;
use App\Http\Controllers\dashboard\roomController;
use App\Http\Controllers\dashboard\tourController;
use App\Http\Controllers\dashboard\tourTimeController;
use App\Http\Controllers\dashboard\postcontroller;
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
    Route::post('/profileChange', [ProfileController::class, 'profileChange']);
    Route::post('/uploadAvatar', [ProfileController::class, 'uploadAvatar']);
});

Route::prefix('dashboard')->group(function () {
    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'index']);
        Route::post('/search', [UserController::class, 'search']);
        Route::get('/create', [UserController::class, 'create']);
        Route::post('/create', [UserController::class, 'store']);
        Route::get('/{id}/edit', [UserController::class, 'edit']);
        Route::post('/{id}', [UserController::class, 'update']);
    });

    Route::prefix('places')->group(function () {
        Route::get('/', [PlacesController::class, 'index']);
        Route::post('/search', [PlacesController::class, 'search']);
        Route::get('/create', [PlacesController::class, 'create']);
        Route::post('/create', [PlacesController::class, 'store']);
        Route::get('/{slug}/edit', [PlacesController::class, 'edit']);
        Route::post('/{slug}', [PlacesController::class, 'update']);
        Route::post('/delete/{id}', [PlacesController::class, 'delete']);
    });

    Route::prefix('Hotel')->group(function () {
        Route::get('/create', [hotelController::class, 'createHotel']);
        Route::post('/create', [hotelController::class, 'storeHotel']);

        Route::get('/widget/{name?}', [roomController::class, 'getWidget']);
        Route::post('/widget/create', [roomController::class, 'storeWidget']);
        Route::post('/widget/delete/{id}', [roomController::class, 'deleteWidget']);

        Route::get('/', [hotelController::class, 'index']);
        Route::post('/search', [hotelController::class, 'search']);
        Route::get('/{slug}/edit', [hotelController::class, 'editHotel']);
        Route::post('/{slug}/edit', [hotelController::class, 'updateHotel']);
        Route::post('/delete/{id}', [hotelController::class, 'deleteHotel']);

        Route::get('/{slug}/room', [roomController::class, 'getRoom']);
        Route::post('/{slug}/room/search', [roomController::class, 'search']);
        Route::get('/{slug}/room/create', [roomController::class, 'getCreateRoom']);
        Route::post('/{slug}/room/create', [roomController::class, 'storeRoom']);
        Route::get('/{slug}/room/{slugRoom}/edit', [roomController::class, 'getEditRoom']);
        Route::post('/{slug}/room/{slugRoom}/edit', [roomController::class, 'updateRoom']);
        Route::post('/{slug}/room/delete/{id}', [roomController::class, 'deleteRoom']);
    });

    Route::prefix('tour')->group(function () {
        Route::get('/', [tourController::class, 'getTours']);
        Route::post('/search', [tourController::class, 'search']);
        Route::get('/create', [tourController::class, 'getCreateTour']);
        Route::post('/create', [tourController::class, 'storeTour']);
        Route::get('/{slug}/edit', [tourController::class, 'getEditTour']);
        Route::post('/{slug}/edit', [tourController::class, 'updateTour']);
        Route::post('/delete/{id}', [tourController::class, 'delete']);

        Route::get('/{slug}/time', [tourTimeController::class, 'getTourtime']);
        Route::post('/{slug}/time/search', [tourTimeController::class, 'search']);
        Route::get('/{slug}/time/create', [tourTimeController::class, 'getCreateTourTime']);
        Route::post('/{slug}/time/create', [tourTimeController::class, 'storeTourTimes']);
        Route::get('/{slug}/time/{id}/edit', [tourTimeController::class, 'getEditTourTime']);
        Route::post('/{slug}/time/{id}/edit', [tourTimeController::class, 'updateTourTime']);
        Route::post('/{slug}/time/delete/{id}', [tourTimeController::class, 'deleteTourTime']);
    });

    Route::prefix('post')->group(function () {
        Route::get('/', [postcontroller::class, 'index']);
        Route::post('/search', [postcontroller::class, 'search']);
        Route::get('/create', [postcontroller::class, 'getCreate']);
        Route::post('/create', [postcontroller::class, 'store']);
        Route::get('/{slug}/edit', [postcontroller::class, 'getEdit']);
        Route::post('/{slug}/edit', [postcontroller::class, 'update']);
        Route::post('/delete/{id}', [postcontroller::class, 'delete']);
    });
});

Route::post('/upload', [hotelController::class, 'upload']);

Route::prefix('hotel')->group(function () {
    Route::get('/home', [homeHotelController::class, 'indexHotel']);
    Route::get('/{slug}', [homeHotelController::class, 'hoteldetail']);
    Route::get('/search/{search?}', [homeHotelController::class, 'searchHotel']);
});

Route::prefix('tour')->group(function () {
    Route::get('/search/{search?}', [homeTourController::class, 'searchTour']);
    Route::get('/', [homeTourController::class, 'show']);
    Route::get('/{slug}', [homeTourController::class, 'tourdetail']);
    // Route::get('/search/search-by-place/{country}', [homeTourController::class, 'searchByPlace']);
});
