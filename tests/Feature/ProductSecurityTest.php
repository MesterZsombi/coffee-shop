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

    $user = User::factory()->create();
    $user->is_admin = false;
    $user->save();

    Sanctum::actingAs($user);
});

it('prevents regular users from creating products', function () {
    $this->postJson('/api/products', [
        'name' => 'Latte',
        'description' => 'Coffee with milk',
        'price' => 40000,
        'currency' => 'VND',
        'display_image_url' => 'https://example.com/latte.jpg',
        'category_id' => $this->product->category_id,
    ])->assertForbidden();

    $this->assertDatabaseMissing('products', [
        'name' => 'Latte',
    ]);
});

it('prevents regular users from uploading product images', function () {
    $this->postJson(
        "/api/products/{$this->product->id}/image",
        [
            'image' => UploadedFile::fake()->image('espresso.jpg'),
        ]
    )->assertForbidden();

    expect($this->product->fresh()->display_image_url)
        ->toBe('https://example.com/espresso.jpg');
});
