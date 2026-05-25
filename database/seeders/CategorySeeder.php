<?php
// database/seeders/CategorySeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Electronics', 'code' => 'ELEC', 'description' => 'Electronic devices and accessories'],
            ['name' => 'Office Supplies', 'code' => 'OFF_SUP', 'description' => 'Office supplies and stationery'],
            ['name' => 'Furniture', 'code' => 'FURN', 'description' => 'Office and home furniture'],
            ['name' => 'Computers', 'code' => 'COMP', 'description' => 'Computers and peripherals'],
            ['name' => 'Software', 'code' => 'SW', 'description' => 'Software licenses and subscriptions'],
            ['name' => 'Services', 'code' => 'SERV', 'description' => 'Professional services'],
            ['name' => 'Raw Materials', 'code' => 'RAW', 'description' => 'Raw materials and components'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}