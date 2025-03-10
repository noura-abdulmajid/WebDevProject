<?php

use App\Http\Resources\ProductResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductReviewController;
use App\Models\Product;
use Illuminate\Support\Facades\View;
use function Pest\Laravel\get;
use function Pest\Laravel\post;



Route::get('/', function() {
    return view('welcome');
});


