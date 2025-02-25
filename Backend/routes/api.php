<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\ForgetPasswordController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\SiteReviewController;
use App\Http\Controllers\LogVisitController;
use App\Http\Controllers\CheckoutController;

Route::prefix('DashShoe')->group(function () {
    //Visit
    Route::post('/log-visit', [LogVisitController::class, 'visit'])->name('visit');
    //Checkout
    Route::post('/checkout', [CheckoutController::class, 'checkout'])->name('checkout');
    // User routes
    Route::post('register', [AuthController::class, 'register'])->name('register');
    Route::post('login', [AuthController::class, 'login'])->name('login');
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/profile', [AuthController::class, 'getProfile'])->name('profile.get');
    Route::put('/profile', [AuthController::class, 'updateProfile'])->name('profile.update');

    // Forgot password and reset password
    Route::post('forgot-password', [ForgetPasswordController::class, 'forgotPassword'])->name('auth.forgot_password');
    Route::post('reset-password', [ForgetPasswordController::class, 'resetPassword'])->name('auth.reset_password');

    // Admin routes
    Route::prefix('admin')->group(function () {
        Route::post('login', [AdminAuthController::class, 'login'])->name('admin.login');
        Route::post('logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
        Route::get('/get_users', [AdminAuthController::class, 'getAllUsers'])->name('admin.users.get');
        Route::delete('/delete_user/{id}', [AdminAuthController::class, 'deleteUser'])->name('admin.user.delete');
        Route::put('/update_user/{id}', [AdminAuthController::class, 'updateUser'])->name('admin.update_user');

        Route::get('test', function () {
            return response()->json(['message' => 'Admin Test Route Works!']);
        })->name('admin.test');
    });
    //Contact
    Route::post('/contact-us', [MessageController::class, 'store'])->name('contact_us');
    //Site
    Route::post('/site-review', [SiteReviewController::class, 'store'])->name('site_review');

    // Test route
    Route::get('test', function () {
        return response()->json(['message' => 'API is working']);
    })->name('test');
});
