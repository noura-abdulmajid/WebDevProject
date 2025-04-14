<?php

use App\Http\Controllers\DiscountCodeController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductReturnController;
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

    // Admin Returns
    Route::get('/get_returns', [AdminUsersController::class, 'getAllReturnRequests'])->name('admin.user.getReturns');
    Route::post('/set_return_received', [AdminUsersController::class, 'setReceived'])->name('admin.user.setReturnReceived');
    Route::post('/confirm_reject_return', [AdminUsersController::class, 'confirmOrRejectReturn'])->name('admin.user.returnConfirmReject');
    route::post('/set_return_refunded', [AdminUsersController::class, 'setRefunded'])->name('admin.user.setReturnRefunded');

    // Admin Orders
    Route::get('/get_orders', [AdminUsersController::class, 'returnAllOrders'])->name('admin.user.getOrders');
    Route::post('/set_order_status', [AdminUsersController::class, 'setOrderStatus'])->name('admin.user.setOrderStatus');
    Route::get('/get_shipping_details', [AdminUsersController::class, 'returnShippingDetails'])->name('admin.user.getShippingDetails');

    // Admin Messages
    Route::get('/get_messages', [AdminUsersController::class, 'returnAllMessages'])->name('admin.user.getMessages');
    Route::post('/respond_message', [AdminUsersController::class, 'respondToMessage'])->name('admin.user.respondMessage');

    // Admin Discount Code
    Route::get('/get_discount_codes', [AdminUsersController::class, 'returnAllDiscountCodes'])->name('admin.user.getDiscountCodes');
    Route::post('/create_discount_code', [AdminUsersController::class, 'createNewDiscountCode'])->name('admin.user.createDiscountCode');
    Route::post('/delete_discount_code', [AdminUsersController::class, 'destroyDiscountCode'])->name('admin.user.deleteDiscountCode');
    Route::post('/update_discount_code', [AdminUsersController::class, 'editDiscountCode'])->name('admin.user.updateDiscountCode');


    Route::get('test', function () {
        return response()->json(['message' => 'Admin Test Route Works!']);
    })->name('admin.test');
});