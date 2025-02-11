<?php

use App\Models\Product;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use App\Http\Controllers\ProductController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/products', [ProductController::class, 'index'])
    -> name('products.index');

Route::get('/products/{id}/', [ProductController::class, 'show'])
    -> name('products.show')
    -> where('id', '[0-9]+'); //where id is a number
