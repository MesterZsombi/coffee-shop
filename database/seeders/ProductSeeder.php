<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $cafe = Category::where('name', 'Cafe')->firstOrFail();
        $chicken = Category::where('name', 'Chicken')->firstOrFail();

        Product::firstOrCreate(
            ['name' => 'Cappuccino'],
            [
                'description' => 'Espresso with steamed milk',
                'price' => 35000,
                'currency' => Product::DEFAULT_CURRENCY,
                'display_image_url' => Product::DEFAULT_IMAGE,
                'category_id' => $cafe->id,
            ]
        );

        Product::firstOrCreate(
            ['name' => 'Chicken Satay Salad'],
            [
                'description' => 'Chicken with peanut satay sauce',
                'price' => 50000,
                'currency' => Product::DEFAULT_CURRENCY,
                'display_image_url' => Product::DEFAULT_IMAGE,
                'category_id' => $chicken->id,
            ]
        );
    }
}
