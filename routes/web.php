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
