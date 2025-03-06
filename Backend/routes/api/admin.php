<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminProductController;


// Admin routes
Route::prefix('admin')->group(function () {
    //Auth
    Route::post('login', [AdminAuthController::class, 'login'])->name('admin.login');
    Route::post('logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

    //Dashboard
    Route::get('/get_users', [AdminDashboardController::class, 'getAllUsers'])->name('admin.users.getUsers');
    Route::delete('/delete_user/{id}', [AdminDashboardController::class, 'deleteUser'])->name('admin.user.deleteUser');
    Route::put('/update_user/{id}', [AdminDashboardController::class, 'updateUser'])->name('admin.updateUser');
    Route::get('/dashboard_status', [AdminDashboardController::class, 'dashboardStats'])->name('admin.dashboardStats');

    //Product
    Route::get('/get_products', [AdminProductController::class, 'getProducts'])->name('admin.user.getProducts');
    Route::post('/add_product', [AdminProductController::class, 'addProduct'])->name('admin.user.addProduct');
    Route::put('/update_product/{id}', [AdminProductController::class, 'updateProduct'])->name('admin.user.updateProduct');
    Route::delete('/delete_product/{id}', [AdminProductController::class, 'deleteProduct'])->name('admin.user.deleteProduct');
    Route::get('/get_product_categories', [AdminProductController::class, 'getProductCategories'])->name('admin.user.getProductCategories');

    //Test
    Route::get('test', function () {
        return response()->json(['message' => 'Admin Test Route Works!']);
    })->name('admin.test');
});
