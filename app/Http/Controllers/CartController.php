<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function add(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1|max:99',
        ]);

        $cart = session()->get('cart', []);
        $id = $product->id;

        if (isset($cart[$id])) {
            $cart[$id]['quantity'] = min(
                99,
                $cart[$id]['quantity'] + $validated['quantity']
            );
        } else {
            $cart[$id] = [
                'quantity' => $validated['quantity'],
            ];
        }

        session()->put('cart', $cart);

        return back()->with('success', 'Product added to cart!');
    }

    public function remove(Product $product): RedirectResponse
    {
        $cart = session()->get('cart', []);

        unset($cart[$product->id]);

        session()->put('cart', $cart);

        return back()->with('success', 'Product removed!');
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1|max:99',
        ]);

        $cart = session()->get('cart', []);

        if (isset($cart[$product->id])) {
            $cart[$product->id]['quantity'] = $validated['quantity'];
            session()->put('cart', $cart);
        }

        return back();
    }
}
