<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Services\InventoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PosApiController extends Controller
{
    /**
     * Get all products with stock > 0 for the POS terminal.
     */
    public function products(): JsonResponse
    {
        $products = Product::with(['category', 'inventory'])
            ->where(function ($q) {
                $q->whereHas('inventory', function ($sub) {
                    $sub->where('quantity_in_stock', '>', 0);
                })->orWhere(function ($sub) {
                    $sub->whereDoesntHave('inventory')
                        ->where('stock', '>', 0);
                });
            })
            ->orderBy('name')
            ->get()
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'barcode' => $product->barcode,
                    'name' => $product->name,
                    'price' => (float) $product->price,
                    'weight' => $product->description ?? '',
                    'category' => $product->category->name ?? 'Uncategorized',
                    'stock' => $product->inventory->quantity_in_stock ?? $product->stock,
                ];
            });

        return response()->json($products);
    }

    /**
     * Place an order with stock deduction.
     */
    public function placeOrder(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'subtotal' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'tax' => 'required|numeric|min:0',
            'total' => 'required|numeric|min:0',
            'amount_received' => 'nullable|numeric|min:0',
            'change_amount' => 'nullable|numeric|min:0',
            'payment_method' => 'required|in:cash,card,mobile',
            'note' => 'nullable|string|max:500',
        ]);

        try {
            return DB::transaction(function () use ($validated, $request) {
                $userId = auth()->id();

                // Create the order
                $order = Order::create([
                    'user_id' => $userId,
                    'subtotal' => $validated['subtotal'],
                    'tax' => $validated['tax'],
                    'total' => $validated['total'],
                    'amount_received' => $validated['amount_received'] ?? $validated['total'],
                    'change_amount' => $validated['change_amount'] ?? 0,
                    'payment_method' => $validated['payment_method'],
                    'status' => 'completed',
                ]);

                // Create order items
                foreach ($validated['items'] as $item) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $item['product_id'],
                        'quantity' => $item['qty'],
                        'price' => $item['price'],
                        'subtotal' => $item['price'] * $item['qty'],
                    ]);
                }

                // Deduct stock using InventoryService
                $inventoryService = app(InventoryService::class);
                $stockItems = array_map(fn($item) => [
                    'product_id' => $item['product_id'],
                    'quantity' => $item['qty'],
                ], $validated['items']);
                $inventoryService->deductStockForSale($stockItems, $order->id, $userId);

                return response()->json([
                    'success' => true,
                    'message' => 'Order placed successfully',
                    'order_id' => $order->id,
                    'change_amount' => (float) ($validated['change_amount'] ?? 0),
                ]);
            });
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }
}
