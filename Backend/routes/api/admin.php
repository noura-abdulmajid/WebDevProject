<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminProductController;
use App\Http\Controllers\Admin\AdminSiteReviewController;
use App\Http\Controllers\Admin\AdminCustomerController;
use App\Http\Controllers\Admin\AdminOrderController;


// Admin routes
Route::prefix('admin')->group(function () {
    //Auth
    Route::post('login', [AdminAuthController::class, 'login'])->name('admin.login');
    Route::post('logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

    //Dashboard
    Route::get('/dashboard_status', [AdminDashboardController::class, 'dashboardStats'])->name('admin.dashboardStats');

    //Customer
    Route::delete('/delete_user/{id}', [AdminCustomerController::class, 'deleteUser'])->name('admin.user.deleteUser');
    Route::put('/update_user/{id}', [AdminCustomerController::class, 'updateUser'])->name('admin.updateUser');
    Route::get('/get_users', [AdminCustomerController::class, 'getAllUsers'])->name('admin.users.getUsers');

    //Customer-EditPage
    Route::get('/customers/{id}', [AdminCustomerController::class, 'show'])->name('admin.customers.show');
    Route::put('/customers/{id}', [AdminCustomerController::class, 'update'])->name('admin.customer.update');
    Route::delete('/customers/{id}', [AdminCustomerController::class, 'destroy'])->name('admin.customer.destroy');

    //Order
    Route::get('/orders/{id}', [AdminOrderController::class, 'show'])->name('admin.order.show');
    Route::put('/orders/{id}', [AdminOrderController::class, 'update'])->name('admin.order.update');
    Route::delete('/orders/{id}', [AdminOrderController::class, 'destroy'])->name('admin.order.destroy');

    //Product
    Route::get('/get_products', [AdminProductController::class, 'getProducts'])->name('admin.user.getProducts');
    Route::post('/add_product', [AdminProductController::class, 'addProduct'])->name('admin.user.addProduct');
    Route::put('/update_product/{id}', [AdminProductController::class, 'updateProduct'])->name('admin.user.updateProduct');
    Route::delete('/delete_product/{id}', [AdminProductController::class, 'deleteProduct'])->name('admin.user.deleteProduct');
    Route::post('/upload_image', [AdminProductController::class, 'uploadImage'])->name('admin.user.uploadImage');

    //SiteReview
    Route::get('/site_review', [AdminSiteReviewController::class, 'getSiteReview'])->name('admin.user.getSiteReview');
    Route::put('/site-reviews/{id}/mark-as-read', [AdminSiteReviewController::class, 'markAsRead'])->name('admin.user.markAsRead');
    Route::put('/site-reviews/{id}/mark-as-replied', [AdminSiteReviewController::class, 'markAsReplied'])->name('admin.user.markAsReplied');
    Route::patch('/site-reviews/reply/{id}', [AdminSiteReviewController::class, 'sendReply'])->name('admin.user.sendReply');

    //Test
    Route::get('test', function () {
        return response()->json(['message' => 'Admin Test Route Works!']);
    })->name('admin.test');
});
