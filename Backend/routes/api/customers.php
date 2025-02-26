<?php

use App\Http\Controllers\Customer\CustomersController;
use App\Http\Controllers\Customer\ForgetPasswordController;
use App\Http\Controllers\Customer\CustomerProfileController;
use Illuminate\Support\Facades\Route;

// User Auth
Route::post('register', [CustomersController::class, 'register'])->name('register');
Route::post('login', [CustomersController::class, 'login'])->name('login');
Route::post('logout', [CustomersController::class, 'logout'])->name('logout');

//User Profile
Route::get('/profile', [CustomerProfileController::class, 'getProfile'])->name('profile.get');
Route::put('/profile', [CustomerProfileController::class, 'updateProfile'])->name('profile.update');

// Forgot password and reset password
Route::post('forgot-password', [ForgetPasswordController::class, 'forgotPassword'])->name('auth.forgot_password');
Route::post('reset-password', [ForgetPasswordController::class, 'resetPassword'])->name('auth.reset_password');
