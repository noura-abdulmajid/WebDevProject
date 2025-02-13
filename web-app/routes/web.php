<?php

use App\Http\Controllers\ProductReviewController;
use App\Models\Product;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use App\Http\Controllers\ProductController;
use function Pest\Laravel\get;
use function Pest\Laravel\post;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/products', [ProductController::class, 'index'])
    -> name('products.index');

Route::get('/products/{id}/', [ProductController::class, 'show'])
    -> name('products.show')
    -> where('id', '[0-9]+'); //where id is a number


Route::post('/products/{id}/save', [ProductController::class, 'save_review'])
    -> name('products.save_review');


Route::post('/products/{id}/favourite', [ProductController::class, 'favourite'])
    -> name('products.favourite');

Route::post('/products/{id}/add_to_cart', [ProductController::class, 'add_to_cart'])
    -> name('products.add_to_cart');


Route::get("/products/search", [ProductController::class, 'search'])
    -> name('products.search');


/*
Route::get("sort_products", [ProductController::class, 'sort'])
    -> name('products.sort');
*/

