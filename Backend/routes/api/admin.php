<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminUsersController;


// Admin routes
Route::prefix('admin')->group(function () {
    Route::post('login', [AdminUsersController::class, 'login'])->name('admin.login');
    Route::post('logout', [AdminUsersController::class, 'logout'])->name('admin.logout');
    Route::get('/get_users', [AdminUsersController::class, 'getAllUsers'])->name('admin.users.getUsers');
    Route::delete('/delete_user/{id}', [AdminUsersController::class, 'deleteUser'])->name('admin.user.deleteUser');
    Route::put('/update_user/{id}', [AdminUsersController::class, 'updateUser'])->name('admin.updateUser');
    Route::get('/get_products', [AdminUsersController::class, 'getProducts'])->name('admin.user.getProducts');

    Route::get('test', function () {
        return response()->json(['message' => 'Admin Test Route Works!']);
    })->name('admin.test');
});