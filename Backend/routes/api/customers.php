<?php

use App\Http\Controllers\Customer\CustomersController;
use App\Http\Controllers\Customer\ForgetPasswordController;
use App\Http\Controllers\Customer\CustomerProfileController;
use App\Http\Controllers\Customer\RefundController;
use App\Http\Controllers\Customer\FavoriteController;
use Illuminate\Support\Facades\Route;

// User Auth
Route::post('register', [CustomersController::class, 'register'])->name('register');
Route::post('login', [CustomersController::class, 'login'])->name('login');
Route::post('logout', [CustomersController::class, 'logout'])->name('logout');

//User Profile
Route::prefix('profile')->group(function () {
    Route::get('/getProfile', [CustomerProfileController::class, 'getProfile'])->name('profile.get');
    Route::put('/', [CustomerProfileController::class, 'updateProfile'])->name('profile.update');

    // Profile Orders
    Route::get('/orders', [CustomerProfileController::class, 'getOrders'])->name('profile.orders.get');

    // Profile Favorites
    Route::get('/favorites', [FavoriteController::class, 'index'])->name('profile.favorites.get');
    Route::post('/favorites/{product}', [FavoriteController::class, 'store'])->name('profile.favorites.store');
    Route::delete('/favorites/{product}', [FavoriteController::class, 'destroy'])->name('profile.favorites.destroy');
});

// Forgot password and reset password
Route::post('forgot-password', [ForgetPasswordController::class, 'forgotPassword'])->name('auth.forgot_password');
Route::post('reset-password', [ForgetPasswordController::class, 'resetPassword'])->name('auth.reset_password');

// Refund routes
Route::middleware('auth:api')->group(function () {
    Route::post('/refunds', [RefundController::class, 'store'])->name('refunds.store');
    Route::get('/refunds', [RefundController::class, 'index'])->name('refunds.index');
    Route::get('/refunds/{refund}', [RefundController::class, 'show'])->name('refunds.show');
});
