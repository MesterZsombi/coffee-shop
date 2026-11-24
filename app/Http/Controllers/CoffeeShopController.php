<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\View\View;

class CoffeeShopController extends Controller
{
    public function index(): View
    {
        $categories = Category::with('products')->get();

        return view('coffee-shop', compact('categories'));
    }
}
