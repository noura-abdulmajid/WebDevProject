<?php

use App\Http\Controllers\CustomersController;
use App\Http\Controllers\ForgetPasswordController;
use Illuminate\Support\Facades\Route;

// User routes
Route::post('register', [CustomersController::class, 'register'])->name('register');
Route::post('login', [CustomersController::class, 'login'])->name('login');
Route::post('logout', [CustomersController::class, 'logout'])->name('logout');
Route::get('/profile', [CustomersController::class, 'getProfile'])->name('profile.get');
Route::put('/profile', [CustomersController::class, 'updateProfile'])->name('profile.update');

// Forgot password and reset password
Route::post('forgot-password', [ForgetPasswordController::class, 'forgotPassword'])->name('auth.forgot_password');
Route::post('reset-password', [ForgetPasswordController::class, 'resetPassword'])->name('auth.reset_password');
