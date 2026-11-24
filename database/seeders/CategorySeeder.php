<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        Category::firstOrCreate(
            ['name' => 'Cafe'],
            ['description' => 'Coffee and hot drinks']
        );

        Category::firstOrCreate(
            ['name' => 'Chicken'],
            ['description' => 'Chicken dishes']
        );
    }
}
