<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/db-test', [DatabaseController::class, 'testConnection']);

//Contact
Route::get('/contact', [MessageController::class, 'create']);
Route::post('/contact', [MessageController::class, 'store']);
Route::get('/contact_confirm', [MessageController::class, 'confirm']);

//Site review
Route::get('/site_review', [SiteReviewController::class, 'create']);
Route::post('/site_review', [SiteReviewController::class, 'store']);
Route::get('/site_review_confirm', [SiteReviewController::class, 'confirm']);

//Shopping Cart
Route::get('/cart', [CartController::class, 'viewCart']);
Route::post('/checkout', [CartController::class, 'checkout']);
Route::get('/checkout/success', [CartController::class, 'success'])->name('checkout.success');
Route::get('/checkout/failed', [CartController::class, 'failed'])->name('checkout.failed');

