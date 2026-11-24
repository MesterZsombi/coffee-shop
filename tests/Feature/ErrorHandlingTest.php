<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('returns a custom 404 page for unknown routes', function () {
    $this->get('/this-page-does-not-exist')
        ->assertNotFound()
        ->assertSee('Page Not Found')
        ->assertSee('Back to Coffee Shop');
});

it('returns 404 when a product does not exist', function () {
    $this->getJson('/api/products/999999')
        ->assertNotFound();
});

it('logs checkout failures and preserves the cart', function () {
    $category = \App\Models\Category::create([
        'name' => 'Coffee',
        'description' => 'Coffee drinks',
    ]);

    $product = \App\Models\Product::create([
        'name' => 'Espresso',
        'description' => 'Strong coffee',
        'price' => 30000,
        'currency' => 'VND',
        'display_image_url' => 'https://example.com/espresso.jpg',
        'category_id' => $category->id,
    ]);

    // Simulate an unexpected database save failure.
    \App\Models\Order::creating(function () {
        throw new \RuntimeException('Simulated database failure');
    });

    \Illuminate\Support\Facades\Log::spy();

    $this->withSession([
        'cart' => [
            $product->id => ['quantity' => 2],
        ],
    ])
    ->from('/checkout')
    ->post('/checkout', [
        'customer_name' => 'Test Customer',
        'email' => 'test@example.com',
        'phone' => '123456789',
        'delivery_address' => '123 Test Street',
    ])
    ->assertRedirect('/checkout')
    ->assertSessionHasErrors('checkout')
    ->assertSessionHas('cart');

    // The failed transaction must not create an order.
    $this->assertDatabaseCount('orders', 0);

    // The error should be logged.
    \Illuminate\Support\Facades\Log::shouldHaveReceived('error')
        ->once()
        ->withArgs(function ($message, $context) {
            return $message === 'Coffee Shop checkout failed'
                && $context['exception_type'] === \RuntimeException::class;
        });
});

