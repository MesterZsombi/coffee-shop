
<?php

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $category = Category::create([
        'name' => 'Coffee',
        'description' => 'Coffee drinks',
    ]);

    $this->product = Product::create([
        'name' => 'Cappuccino',
        'description' => 'Coffee with milk',
        'price' => 35000,
        'currency' => 'VND',
        'display_image_url' => 'https://example.com/coffee.jpg',
        'category_id' => $category->id,
    ]);
});

it('redirects to the menu when the cart is empty', function () {
    $this->get('/checkout')
        ->assertRedirect(route('coffee-shop.index'));
});

it('displays checkout with products from the session', function () {
    $this->withSession([
        'cart' => [
            $this->product->id => ['quantity' => 2],
        ],
    ])->get('/checkout')
        ->assertOk()
        ->assertSee('Cappuccino')
        ->assertSee('70,000');
});

it('creates an order and clears the cart', function () {
    $this->withSession([
        'cart' => [
            $this->product->id => ['quantity' => 2],
        ],
    ])->post('/checkout', [
        'customer_name' => 'Test Customer',
        'email' => 'test@example.com',
        'phone' => '123456789',
        'delivery_address' => '123 Test Street',
    ])->assertRedirect();

    $order = Order::with('items')->firstOrFail();

    expect($order->total_amount)->toEqual(70000);
    expect($order->items)->toHaveCount(1);
    expect($order->items->first()->quantity)->toBe(2);

    $this->assertDatabaseHas('orders', [
        'email' => 'test@example.com',
        'status' => 'pending',
    ]);

    $this->assertDatabaseHas('order_items', [
        'order_id' => $order->id,
        'product_name' => 'Cappuccino',
        'quantity' => 2,
        'subtotal' => 70000,
    ]);

    $this->assertEmpty(session('cart', []));
});

it('rejects checkout when customer data is invalid', function () {
    $this->withSession([
        'cart' => [
            $this->product->id => ['quantity' => 1],
        ],
    ])->post('/checkout', [
        'customer_name' => '',
        'email' => 'not-an-email',
        'phone' => '',
        'delivery_address' => '',
    ])->assertSessionHasErrors([
        'customer_name',
        'email',
        'phone',
        'delivery_address',
    ]);

    expect(Order::count())->toBe(0);
});

it('does not create an order when a cart product no longer exists', function () {
    $this->withSession([
        'cart' => [
            999999 => ['quantity' => 1],
        ],
    ])->post('/checkout', [
        'customer_name' => 'Test Customer',
        'email' => 'test@example.com',
        'phone' => '123456789',
        'delivery_address' => '123 Test Street',
    ])->assertSessionHasErrors('cart');

    expect(Order::count())->toBe(0);
});
