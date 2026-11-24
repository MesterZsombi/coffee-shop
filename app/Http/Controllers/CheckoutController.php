<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Throwable;
use App\Mail\OrderConfirmation;
use Illuminate\Support\Facades\Mail;

class CheckoutController extends Controller
{
    public function create(Request $request): View|RedirectResponse
    {
        $cart = $request->session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('coffee-shop.index')
                ->with('error', 'Your cart is empty.');
        }

        $products = Product::whereIn('id', array_keys($cart))->get();

        $items = $products->map(function ($product) use ($cart) {
            $quantity = (int) $cart[$product->id]['quantity'];

            return [
                'product' => $product,
                'quantity' => $quantity,
                'subtotal' => $product->price * $quantity,
            ];
        });

        $total = $items->sum('subtotal');

        return view('checkout', compact('items', 'total'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:30',
            'delivery_address' => 'required|string|max:1000',
        ]);

        $cart = $request->session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('coffee-shop.index')
                ->with('error', 'Your cart is empty.');
        }

        try {
            $order = DB::transaction(function () use ($cart, $validated) {
                $products = Product::whereIn('id', array_keys($cart))->get();

                if ($products->count() !== count($cart)) {
                    throw ValidationException::withMessages([
                        'cart' => 'Some products are no longer available.',
                    ]);
                }

                $total = 0;
                $items = [];

                foreach ($products as $product) {
                    $quantity = (int) $cart[$product->id]['quantity'];

                    if ($quantity < 1 || $quantity > 99) {
                        throw ValidationException::withMessages([
                            'cart' => 'Invalid product quantity.',
                        ]);
                    }

                    $subtotal = $product->price * $quantity;
                    $total += $subtotal;

                    $items[] = [
                        'product_id' => $product->id,
                        'product_name' => $product->name,
                        'quantity' => $quantity,
                        'unit_price' => $product->price,
                        'subtotal' => $subtotal,
                    ];
                }

                $order = Order::create([
                    ...$validated,
                    'total_amount' => $total,
                    'currency' => 'VND',
                    'status' => 'pending',
                ]);

                $order->items()->createMany($items);

                return $order;
            });

        } catch (ValidationException $e) {
            // Expected validation errors should be shown to the user.
            throw $e;

        } catch (Throwable $e) {
            // Do not log customer details or potentially sensitive exception messages.
            Log::error('Coffee Shop checkout failed', [
                'exception_type' => get_class($e),
            ]);

            return back()
                ->withErrors([
                    'checkout' => 'Something went wrong. Please try again.',
                ])
                ->withInput();
        }

        // Clear the cart only after the order is successfully saved.
        $request->session()->forget('cart');
        $request->session()->put('last_order_id', $order->id);

        Log::info('Coffee Shop order created', [
            'order_id' => $order->id,
            'total_amount' => $order->total_amount,
            'currency' => $order->currency,
        ]);
        try {
                Mail::to($order->email)
                    ->queue(new OrderConfirmation($order));
            } catch (Throwable $e) {
                Log::error('Order confirmation could not be queued', [
                    'order_id' => $order->id,
                    'exception_type' => get_class($e),
                ]);
            }
        

        return redirect()->route('checkout.success', $order)
            ->with('success', 'Your order has been placed!');
    }

    public function success(Request $request, Order $order): View
    {
        abort_unless(
            $request->session()->get('last_order_id') === $order->id,
            403
        );

        return view('checkout-success', compact('order'));
    }
}
