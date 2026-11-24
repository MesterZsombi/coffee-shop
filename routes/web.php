<?php

use App\Http\Controllers\CoffeeShopController;
use App\Http\Controllers\CartController;
use Illuminate\Support\Facades\Route;

Route::get('/', [CoffeeShopController::class, 'index'])
    ->name('coffee-shop.index');

Route::post('/cart/add/{product}', [CartController::class, 'add'])
    ->name('cart.add');

Route::patch('/cart/update/{product}', [CartController::class, 'update'])
    ->name('cart.update');

Route::delete('/cart/remove/{product}', [CartController::class, 'remove'])
    ->name('cart.remove');


Route::get('/checkout', [\App\Http\Controllers\CheckoutController::class, 'create'])
    ->name('checkout.create');

Route::post('/checkout', [\App\Http\Controllers\CheckoutController::class, 'store'])
    ->name('checkout.store');

Route::get('/checkout/success/{order}', [\App\Http\Controllers\CheckoutController::class, 'success'])
    ->name('checkout.success');

