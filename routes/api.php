<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\WalletController;
use App\Http\Controllers\Api\DisputeController;
use App\Http\Controllers\Api\NotificationController;

// ── Public ──────────────────────────────────────────
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login',    [AuthController::class, 'login']);
Route::get('/services',       [ServiceController::class, 'index']);
Route::get('/services/{id}',  [ServiceController::class, 'show']);

// ── Protected ────────────────────────────────────────
Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me',      [AuthController::class, 'me']);

    // Services (provider)
    Route::post('/services',         [ServiceController::class, 'store']);
    Route::put('/services/{id}',     [ServiceController::class, 'update']);
    Route::delete('/services/{id}',  [ServiceController::class, 'destroy']);

    // Bookings
    Route::get('/bookings',             [BookingController::class, 'index']);
    Route::get('/bookings/{id}',        [BookingController::class, 'show']);
    Route::post('/bookings',            [BookingController::class, 'store']);
    Route::put('/bookings/{id}/cancel', [BookingController::class, 'cancel']);

    // Reviews
    Route::post('/services/{id}/reviews', [ReviewController::class, 'store']);
    Route::get('/services/{id}/reviews',  [ReviewController::class, 'index']);

    // Wallet
    Route::get('/wallet',           [WalletController::class, 'show']);
    Route::post('/wallet/topup',    [WalletController::class, 'topup']);

    // Disputes
    Route::get('/disputes',         [DisputeController::class, 'index']);
    Route::post('/disputes',        [DisputeController::class, 'store']);
    Route::get('/disputes/{id}',    [DisputeController::class, 'show']);

    // Notifications
    Route::get('/notifications',          [NotificationController::class, 'index']);
    Route::put('/notifications/{id}/read',[NotificationController::class, 'markRead']);
});