<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create categories
        $beverages = Category::create(['name' => 'Beverages', 'icon' => '🥤']);
        $snacks    = Category::create(['name' => 'Snacks', 'icon' => '🍿']);
        $dairy     = Category::create(['name' => 'Dairy', 'icon' => '🧀']);
        $bakery    = Category::create(['name' => 'Bakery', 'icon' => '🍞']);

        // Beverages
        Product::create(['name' => 'Coca-Cola 500ml', 'barcode' => '5449000000996', 'price' => 350.00, 'stock' => 120, 'category_id' => $beverages->id]);
        Product::create(['name' => 'Sprite 500ml', 'barcode' => '5449000001498', 'price' => 350.00, 'stock' => 85, 'category_id' => $beverages->id]);
        Product::create(['name' => 'Elephant House Ginger Beer', 'barcode' => '8901234567890', 'price' => 180.00, 'stock' => 200, 'category_id' => $beverages->id]);

        // Snacks
        Product::create(['name' => 'Munchee Lemon Puff', 'barcode' => '4800016123456', 'price' => 220.00, 'stock' => 60, 'category_id' => $snacks->id]);
        Product::create(['name' => 'Maliban Gold Marie', 'barcode' => '4800016234567', 'price' => 190.00, 'stock' => 75, 'category_id' => $snacks->id]);
        Product::create(['name' => 'CBL Tikiri Mari', 'barcode' => '4800016345678', 'price' => 150.00, 'stock' => 90, 'category_id' => $snacks->id]);

        // Dairy
        Product::create(['name' => 'Anchor Fresh Milk 1L', 'barcode' => '9415007001234', 'price' => 620.00, 'stock' => 40, 'category_id' => $dairy->id]);
        Product::create(['name' => 'Highland Curd 400g', 'barcode' => '9415007002345', 'price' => 280.00, 'stock' => 55, 'category_id' => $dairy->id]);
        Product::create(['name' => 'Newdale Yoghurt Strawberry', 'barcode' => '9415007003456', 'price' => 150.00, 'stock' => 80, 'category_id' => $dairy->id]);

        // Bakery
        Product::create(['name' => 'Sliced Bread White', 'barcode' => '7501234567001', 'price' => 420.00, 'stock' => 30, 'category_id' => $bakery->id]);
        Product::create(['name' => 'Fish Bun', 'barcode' => '7501234567002', 'price' => 120.00, 'stock' => 50, 'category_id' => $bakery->id]);
        Product::create(['name' => 'Chocolate Croissant', 'barcode' => '7501234567003', 'price' => 280.00, 'stock' => 25, 'category_id' => $bakery->id]);
    }
}
