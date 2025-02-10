<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DatabaseController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\SiteReviewController;

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

