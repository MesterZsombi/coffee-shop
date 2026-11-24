<?php

use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ProductImageController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Authenticated user information
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Public endpoints - anyone can view products
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{product}', [ProductController::class, 'show']);

// Admin-only endpoints - product management
Route::middleware(['auth:sanctum', 'can:manage-products'])
    ->group(function () {
        Route::post('/products', [ProductController::class, 'store']);

        Route::put('/products/{product}', [ProductController::class, 'update']);

        Route::patch('/products/{product}', [ProductController::class, 'update']);

        Route::delete('/products/{product}', [ProductController::class, 'destroy']);

        Route::post('/products/{product}/image', [
            ProductImageController::class,
            'store',
        ])->name('api.products.image.store');
    });
