<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->words(2, true),
            'description' => fake()->paragraph(),
            'price' => fake()->numberBetween(20000, 100000),
            'currency' => Product::DEFAULT_CURRENCY,
            'display_image_url' => Product::DEFAULT_IMAGE,
            'category_id' => Category::factory(),
        ];
    }
}
