<?php

use App\Http\Controllers\CustomersController;
use App\Http\Controllers\AdminUsersController;
use App\Http\Controllers\ForgetPasswordController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\SiteReviewController;
use App\Http\Controllers\LogVisitController;
use App\Http\Controllers\CheckoutController;

Route::prefix('DashShoe')->group(function () {
    require base_path('routes/api/customers.php');
    require base_path('routes/api/admin.php');
    //Visit
    Route::post('/log-visit', [LogVisitController::class, 'visit'])->name('visit');
    //Checkout
    Route::post('/checkout', [CheckoutController::class, 'checkout'])->name('checkout');
    //Contact
    Route::post('/contact-us', [MessageController::class, 'store'])->name('contact_us');
    //Site
    Route::post('/site-review', [SiteReviewController::class, 'store'])->name('site_review');
    // Test route
    Route::post('test', function (Illuminate\Http\Request $request) {
        Log::info('Received JSON:', $request->json()->all());
        return response()->json(['message' => 'Received', 'data' => $request->json()->all()]);
    })->name('test');
});
