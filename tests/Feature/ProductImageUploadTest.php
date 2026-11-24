<?php

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

beforeEach(function () {
    Storage::fake('public');

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

it('allows authenticated users to upload a product image', function () {
    $admin = User::factory()->create();
    $admin->is_admin = true;
    $admin->save();

    Sanctum::actingAs($admin);

    $response = $this->postJson(
        "/api/products/{$this->product->id}/image",
        [
            'image' => UploadedFile::fake()->image('espresso.jpg'),
        ]
    );

    $response->assertOk()
        ->assertJsonPath('product_id', $this->product->id)
        ->assertJsonPath('message', 'Product image uploaded successfully.');

    $path = $response->json('path');

    Storage::disk('public')->assertExists($path);

    expect($this->product->fresh()->display_image_url)
        ->toContain('/storage/products/');
});

it('rejects unauthenticated uploads', function () {
    $this->postJson(
        "/api/products/{$this->product->id}/image",
        [
            'image' => UploadedFile::fake()->image('espresso.jpg'),
        ]
    )->assertUnauthorized();
});

it('rejects files that are not images', function () {
    $admin = User::factory()->create();
    $admin->is_admin = true;
    $admin->save();

    Sanctum::actingAs($admin);

    $this->postJson(
        "/api/products/{$this->product->id}/image",
        [
            'image' => UploadedFile::fake()->create('document.pdf', 100),
        ]
    )->assertUnprocessable()
        ->assertJsonValidationErrors('image');
});

it('rejects images larger than 2 MB', function () {
    $admin = User::factory()->create();
    $admin->is_admin = true;
    $admin->save();

    Sanctum::actingAs($admin);

    $this->postJson(
        "/api/products/{$this->product->id}/image",
        [
            'image' => UploadedFile::fake()->image('large.jpg')->size(3000),
        ]
    )->assertUnprocessable()
        ->assertJsonValidationErrors('image');
});
