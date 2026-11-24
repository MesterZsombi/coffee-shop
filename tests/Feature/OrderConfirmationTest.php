<?php

use App\Mail\OrderConfirmation;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;

uses(RefreshDatabase::class);

beforeEach(function () {
    Mail::fake();

    $category = Category::create([
        'name' => 'Coffee',
        'description' => 'Coffee drinks',
    ]);

    $this->product = Product::create([
        'name' => 'Espresso',
        'description' => 'Strong black coffee',
        'price' => 30000,
        'currency' => 'VND',
        'display_image_url' => 'https://example.com/espresso.jpg',
        'category_id' => $category->id,
    ]);
});

it('queues an order confirmation after a successful checkout', function () {
    $this->withSession([
        'cart' => [
            $this->product->id => ['quantity' => 2],
        ],
    ])->post(route('checkout.store'), [
        'customer_name' => 'Test Customer',
        'email' => 'test@example.com',
        'phone' => '123456789',
        'delivery_address' => '123 Test Street',
    ])->assertRedirect();

    $order = Order::firstOrFail();

    Mail::assertQueued(OrderConfirmation::class, function ($mail) use ($order) {
        return $mail->hasTo('test@example.com')
            && $mail->order->id === $order->id;
    });

    Mail::assertQueuedCount(1);
});

it('does not queue an email when checkout validation fails', function () {
    $this->withSession([
        'cart' => [
            $this->product->id => ['quantity' => 1],
        ],
    ])->post(route('checkout.store'), [
        'customer_name' => '',
        'email' => 'invalid-email',
        'phone' => '',
        'delivery_address' => '',
    ])->assertSessionHasErrors();

    Mail::assertNothingOutgoing();
    $this->assertDatabaseCount('orders', 0);
});

it('does not queue an email when a product is missing', function () {
    $this->withSession([
        'cart' => [
            999999 => ['quantity' => 1],
        ],
    ])->post(route('checkout.store'), [
        'customer_name' => 'Test Customer',
        'email' => 'test@example.com',
        'phone' => '123456789',
        'delivery_address' => '123 Test Street',
    ])->assertSessionHasErrors('cart');

    Mail::assertNothingOutgoing();
    $this->assertDatabaseCount('orders', 0);
});
