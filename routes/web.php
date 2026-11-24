<?php

use App\Http\Controllers\CoffeeShopController;
use Illuminate\Support\Facades\Route;

Route::get('/', [CoffeeShopController::class, 'index'])
    ->name('coffee-shop.index');
