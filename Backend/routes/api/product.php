<?php

use App\Http\Controllers\Product\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/products', [ProductController::class, 'index']);

Route::get('/products/{id}/', [ProductController::class, 'show'])
    -> name('products.show');

Route::post('/products/{id}/save', [ProductController::class, 'save_review'])
    -> name('products.save_review');

Route::post('/products/{id}/add_to_cart', [ProductController::class, 'add_to_cart'])
    -> name('products.add_to_cart');

Route::get("/products/search", [ProductController::class, 'search'])
    -> name('products.search');
