<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\View\View;

class CoffeeShopController extends Controller
{
    public function index(): View
    {
        $categories = Category::with('products')->get();

        $cart = session()->get('cart', []);

        $cartProducts = Product::whereIn(
            'id',
            array_keys($cart)
        )->get();

        $cartItems = $cartProducts->map(function ($product) use ($cart) {
            $quantity = $cart[$product->id]['quantity'];

            return [
                'product' => $product,
                'quantity' => $quantity,
                'subtotal' => $product->price * $quantity,
            ];
        });

        $cartTotal = $cartItems->sum('subtotal');
        $cartCount = $cartItems->sum('quantity');

        return view('coffee-shop', compact(
            'categories',
            'cartItems',
            'cartTotal',
            'cartCount'
        ));
    }
}
