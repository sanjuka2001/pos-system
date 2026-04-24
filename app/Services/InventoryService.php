<?php

namespace App\Services;

use App\Models\Inventory;
use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Support\Facades\DB;

class InventoryService
{
    /**
     * Deduct stock for a sale. All-or-nothing inside a DB transaction.
     *
     * @param  array  $items  Array of ['product_id' => int, 'quantity' => int]
     * @param  int    $orderId   The order reference ID
     * @param  int    $userId    The user who made the sale
     * @return void
     *
     * @throws \Exception If any item has insufficient stock
     */
    public function deductStockForSale(array $items, int $orderId, int $userId): void
    {
        DB::transaction(function () use ($items, $orderId, $userId) {
            // First pass: validate ALL items have sufficient stock
            foreach ($items as $item) {
                $inventory = Inventory::where('product_id', $item['product_id'])
                    ->lockForUpdate()
                    ->first();

                if (!$inventory || $inventory->quantity_in_stock < $item['quantity']) {
                    $product = Product::find($item['product_id']);
                    $name = $product ? $product->name : 'Unknown product';
                    throw new \Exception("Insufficient stock for {$name}");
                }
            }

            // Second pass: deduct all items
            foreach ($items as $item) {
                $inventory = Inventory::where('product_id', $item['product_id'])
                    ->lockForUpdate()
                    ->first();

                $previousStock = $inventory->quantity_in_stock;
                $newStock = $previousStock - $item['quantity'];

                $inventory->update(['quantity_in_stock' => $newStock]);

                // Sync products.stock for backward compatibility
                Product::where('id', $item['product_id'])
                    ->update(['stock' => $newStock]);

                // Record movement
                StockMovement::create([
                    'product_id' => $item['product_id'],
                    'type' => 'sale',
                    'quantity' => -$item['quantity'],
                    'previous_stock' => $previousStock,
                    'new_stock' => $newStock,
                    'reference_id' => $orderId,
                    'note' => "Sale - Order #{$orderId}",
                    'created_by' => $userId,
                ]);
            }
        });
    }

    /**
     * Restock a product (add stock).
     */
    public function restockProduct(int $productId, int $quantity, int $userId, ?string $note = null): void
    {
        DB::transaction(function () use ($productId, $quantity, $userId, $note) {
            $inventory = Inventory::where('product_id', $productId)
                ->lockForUpdate()
                ->firstOrFail();

            $previousStock = $inventory->quantity_in_stock;
            $newStock = $previousStock + $quantity;

            $inventory->update(['quantity_in_stock' => $newStock]);

            // Sync products.stock
            Product::where('id', $productId)->update(['stock' => $newStock]);

            StockMovement::create([
                'product_id' => $productId,
                'type' => 'restock',
                'quantity' => $quantity,
                'previous_stock' => $previousStock,
                'new_stock' => $newStock,
                'reference_id' => null,
                'note' => $note ?? 'Manual restock',
                'created_by' => $userId,
            ]);
        });
    }

    /**
     * Adjust stock to an exact value (manual adjustment).
     */
    public function adjustStock(int $productId, int $newQuantity, int $userId, ?string $note = null): void
    {
        DB::transaction(function () use ($productId, $newQuantity, $userId, $note) {
            $inventory = Inventory::where('product_id', $productId)
                ->lockForUpdate()
                ->firstOrFail();

            $previousStock = $inventory->quantity_in_stock;
            $difference = $newQuantity - $previousStock;

            $inventory->update(['quantity_in_stock' => $newQuantity]);

            // Sync products.stock
            Product::where('id', $productId)->update(['stock' => $newQuantity]);

            StockMovement::create([
                'product_id' => $productId,
                'type' => 'adjustment',
                'quantity' => $difference,
                'previous_stock' => $previousStock,
                'new_stock' => $newQuantity,
                'reference_id' => null,
                'note' => $note ?? 'Manual stock adjustment',
                'created_by' => $userId,
            ]);
        });
    }

    /**
     * Get all products with low stock.
     */
    public function getLowStockProducts()
    {
        return Inventory::with('product.category')
            ->whereColumn('quantity_in_stock', '<=', 'low_stock_alert')
            ->where('quantity_in_stock', '>', 0)
            ->get();
    }

    /**
     * Get count of low stock products.
     */
    public function getLowStockCount(): int
    {
        return Inventory::whereColumn('quantity_in_stock', '<=', 'low_stock_alert')
            ->where('quantity_in_stock', '>', 0)
            ->count();
    }

    /**
     * Get count of out-of-stock products.
     */
    public function getOutOfStockCount(): int
    {
        return Inventory::where('quantity_in_stock', '<=', 0)->count();
    }
}
