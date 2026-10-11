<?php

use App\Http\Controllers\Api\ProductSearchController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/products/search', ProductSearchController::class)
    ->name('products.search');
