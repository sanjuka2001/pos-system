<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Inventory;
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
        $beverages = Category::create(['name' => 'Beverages', 'icon' => '🥤', 'description' => 'Soft drinks, juices, and water']);
        $snacks    = Category::create(['name' => 'Snacks', 'icon' => '🍿', 'description' => 'Biscuits, chips, and snack items']);
        $dairy     = Category::create(['name' => 'Dairy', 'icon' => '🧀', 'description' => 'Milk, yoghurt, and cheese products']);
        $bakery    = Category::create(['name' => 'Bakery', 'icon' => '🍞', 'description' => 'Bread, buns, and pastries']);

        // Helper to create product + inventory in one go
        $createProduct = function ($data, $stockQty, $alertLevel = 10) {
            $product = Product::create(array_merge($data, ['stock' => $stockQty]));
            Inventory::create([
                'product_id' => $product->id,
                'quantity_in_stock' => $stockQty,
                'low_stock_alert' => $alertLevel,
            ]);
            return $product;
        };

        // Beverages
        $createProduct(['name' => 'Coca-Cola 500ml', 'sku' => 'BEV-001', 'barcode' => '5449000000996', 'price' => 350.00, 'cost_price' => 280.00, 'category_id' => $beverages->id], 120);
        $createProduct(['name' => 'Sprite 500ml', 'sku' => 'BEV-002', 'barcode' => '5449000001498', 'price' => 350.00, 'cost_price' => 280.00, 'category_id' => $beverages->id], 85);
        $createProduct(['name' => 'Elephant House Ginger Beer', 'sku' => 'BEV-003', 'barcode' => '8901234567890', 'price' => 180.00, 'cost_price' => 120.00, 'category_id' => $beverages->id], 200);

        // Snacks
        $createProduct(['name' => 'Munchee Lemon Puff', 'sku' => 'SNK-001', 'barcode' => '4800016123456', 'price' => 220.00, 'cost_price' => 160.00, 'category_id' => $snacks->id], 8, 15); // LOW STOCK
        $createProduct(['name' => 'Maliban Gold Marie', 'sku' => 'SNK-002', 'barcode' => '4800016234567', 'price' => 190.00, 'cost_price' => 130.00, 'category_id' => $snacks->id], 75);
        $createProduct(['name' => 'CBL Tikiri Mari', 'sku' => 'SNK-003', 'barcode' => '4800016345678', 'price' => 150.00, 'cost_price' => 100.00, 'category_id' => $snacks->id], 5, 10); // LOW STOCK

        // Dairy
        $createProduct(['name' => 'Anchor Fresh Milk 1L', 'sku' => 'DRY-001', 'barcode' => '9415007001234', 'price' => 620.00, 'cost_price' => 500.00, 'category_id' => $dairy->id], 40);
        $createProduct(['name' => 'Highland Curd 400g', 'sku' => 'DRY-002', 'barcode' => '9415007002345', 'price' => 280.00, 'cost_price' => 200.00, 'category_id' => $dairy->id], 0, 10); // OUT OF STOCK
        $createProduct(['name' => 'Newdale Yoghurt Strawberry', 'sku' => 'DRY-003', 'barcode' => '9415007003456', 'price' => 150.00, 'cost_price' => 100.00, 'category_id' => $dairy->id], 80);

        // Bakery
        $createProduct(['name' => 'Sliced Bread White', 'sku' => 'BKR-001', 'barcode' => '7501234567001', 'price' => 420.00, 'cost_price' => 320.00, 'category_id' => $bakery->id], 30);
        $createProduct(['name' => 'Fish Bun', 'sku' => 'BKR-002', 'barcode' => '7501234567002', 'price' => 120.00, 'cost_price' => 80.00, 'category_id' => $bakery->id], 3, 10); // LOW STOCK
        $createProduct(['name' => 'Chocolate Croissant', 'sku' => 'BKR-003', 'barcode' => '7501234567003', 'price' => 280.00, 'cost_price' => 180.00, 'category_id' => $bakery->id], 25);
    }
}
