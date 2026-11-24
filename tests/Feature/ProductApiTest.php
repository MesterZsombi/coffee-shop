<?php

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

test('anyone can list products', function () {
    $response = $this->getJson('/api/products');

    $response->assertOk();
});

test('unauthenticated users cannot create products', function () {
    $response = $this->postJson('/api/products', [
        'name' => 'Latte',
    ]);

    $response->assertUnauthorized();
});

test('authenticated users can create products', function () {
    Sanctum::actingAs(User::factory()->create());

    $category = Category::create([
        'name' => 'Coffee',
        'description' => 'Coffee drinks',
    ]);

    $response = $this->postJson('/api/products', [
        'name' => 'Latte',
        'description' => 'Coffee with milk',
        'price' => 40000,
        'currency' => 'VND',
        'display_image_url' => 'https://example.com/latte.jpg',
        'category_id' => $category->id,
    ]);

    $response->assertCreated();

    $this->assertDatabaseHas('products', [
        'name' => 'Latte',
        'price' => 40000,
    ]);
});

test('missing products return 404', function () {
    $response = $this->getJson('/api/products/99999');

    $response->assertNotFound();
});

test('product creation requires valid data', function () {
    Sanctum::actingAs(User::factory()->create());

    $response = $this->postJson('/api/products', [
        'name' => '',
        'price' => -100,
    ]);

    $response->assertUnprocessable();
    $response->assertJsonValidationErrors([
        'name',
        'description',
        'price',
        'currency',
        'display_image_url',
    ]);
});
