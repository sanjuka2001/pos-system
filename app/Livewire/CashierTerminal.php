<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Inventory;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Services\InventoryService;
use Livewire\Attributes\Computed;
use Livewire\Component;

class CashierTerminal extends Component
{
    public string $search = '';
    public ?int $categoryFilter = null;
    public array $cart = [];
    public string $amountReceived = '';

    /**
     * Handle barcode scan submission (triggered on Enter key).
     * Barcode scanners typically type characters rapidly then send Enter.
     * This does an exact barcode lookup, adds to cart, clears the input,
     * and dispatches a browser event to re-focus for the next scan.
     */
    public function scanBarcode(): void
    {
        if (empty(trim($this->search))) {
            return;
        }

        $product = Product::with('inventory')->where('barcode', trim($this->search))->first();

        if ($product) {
            $this->addToCart($product->id);
            $this->search = '';
            $this->dispatch('barcode-scanned');
        } else {
            // No exact match — try a name search and add first result
            $product = Product::with('inventory')
                ->where('name', 'like', '%' . trim($this->search) . '%')
                ->where(function ($q) {
                    $q->whereHas('inventory', function ($sub) {
                        $sub->where('quantity_in_stock', '>', 0);
                    })->orWhere(function ($sub) {
                        $sub->whereDoesntHave('inventory')->where('stock', '>', 0);
                    });
                })
                ->first();

            if ($product) {
                $this->addToCart($product->id);
                $this->search = '';
                $this->dispatch('barcode-scanned');
            }
        }
    }

    /**
     * Add a product to the cart or increment its quantity if already present.
     */
    public function addToCart(int $productId): void
    {
        $product = Product::with('inventory')->find($productId);

        if (!$product) {
            return;
        }

        $availableStock = $product->inventory->quantity_in_stock ?? $product->stock;

        if ($availableStock <= 0) {
            $this->dispatch('notification', type: 'error', message: "Insufficient stock for {$product->name}");
            return;
        }

        $key = (string) $productId;

        if (isset($this->cart[$key])) {
            if ($this->cart[$key]['qty'] < $availableStock) {
                $this->cart[$key]['qty']++;
                $this->cart[$key]['line_total'] = $this->cart[$key]['qty'] * $product->price;
            }
        } else {
            $this->cart[$key] = [
                'id'         => $product->id,
                'name'       => $product->name,
                'price'      => (float) $product->price,
                'qty'        => 1,
                'line_total' => (float) $product->price,
                'stock'      => $availableStock,
            ];
        }
    }

    /**
     * Increment quantity for a cart item.
     */
    public function incrementQty(int $productId): void
    {
        $key = (string) $productId;

        if (isset($this->cart[$key]) && $this->cart[$key]['qty'] < $this->cart[$key]['stock']) {
            $this->cart[$key]['qty']++;
            $this->cart[$key]['line_total'] = $this->cart[$key]['qty'] * $this->cart[$key]['price'];
        }
    }

    /**
     * Decrement quantity for a cart item. Removes if quantity reaches 0.
     */
    public function decrementQty(int $productId): void
    {
        $key = (string) $productId;

        if (isset($this->cart[$key])) {
            $this->cart[$key]['qty']--;

            if ($this->cart[$key]['qty'] <= 0) {
                unset($this->cart[$key]);
            } else {
                $this->cart[$key]['line_total'] = $this->cart[$key]['qty'] * $this->cart[$key]['price'];
            }
        }
    }

    /**
     * Remove an item from the cart entirely.
     */
    public function removeFromCart(int $productId): void
    {
        unset($this->cart[(string) $productId]);
    }

    /**
     * Clear the entire cart and reset amount received.
     */
    public function clearCart(): void
    {
        $this->cart = [];
        $this->amountReceived = '';
    }

    /**
     * Filter products by category.
     */
    public function filterByCategory(?int $categoryId): void
    {
        $this->categoryFilter = $categoryId;
    }

    /**
     * Place order: create order, deduct stock (all-or-nothing), clear cart.
     */
    public function placeOrder(): void
    {
        // Validate cart is not empty
        if (empty($this->cart)) {
            $this->dispatch('notification', type: 'error', message: 'Cart is empty. Add items before placing an order.');
            return;
        }

        // Validate amount received
        $received = (float) ($this->amountReceived ?: 0);
        if ($received < $this->grandTotal) {
            $this->dispatch('notification', type: 'error', message: 'Amount received is less than the total. Please enter the correct amount.');
            return;
        }

        try {
            // Create order
            $order = Order::create([
                'user_id' => auth()->id(),
                'subtotal' => $this->subtotal,
                'tax' => $this->tax,
                'total' => $this->grandTotal,
                'amount_received' => $received,
                'change_amount' => $received - $this->grandTotal,
                'payment_method' => 'cash',
                'status' => 'completed',
            ]);

            // Create order items
            $stockItems = [];
            foreach ($this->cart as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['id'],
                    'quantity' => $item['qty'],
                    'price' => $item['price'],
                    'subtotal' => $item['line_total'],
                ]);

                $stockItems[] = [
                    'product_id' => $item['id'],
                    'quantity' => $item['qty'],
                ];
            }

            // Deduct stock (all-or-nothing inside DB transaction)
            $inventoryService = app(InventoryService::class);
            $inventoryService->deductStockForSale($stockItems, $order->id, auth()->id());

            // Success — clear cart
            $this->cart = [];
            $this->amountReceived = '';

            $this->dispatch('notification', type: 'success', message: "Order #{$order->id} placed successfully! Change: LKR " . number_format($order->change_amount, 2));
            $this->dispatch('order-placed');

        } catch (\Exception $e) {
            // If stock deduction fails, delete the order
            if (isset($order)) {
                $order->items()->delete();
                $order->delete();
            }

            $this->dispatch('notification', type: 'error', message: $e->getMessage());
        }
    }

    /**
     * Cart subtotal (sum of all line totals).
     */
    #[Computed]
    public function subtotal(): float
    {
        return collect($this->cart)->sum('line_total');
    }

    /**
     * Tax amount (15% VAT).
     */
    #[Computed]
    public function tax(): float
    {
        return $this->subtotal * 0.15;
    }

    /**
     * Grand total (subtotal + tax).
     */
    #[Computed]
    public function grandTotal(): float
    {
        return $this->subtotal + $this->tax;
    }

    /**
     * Balance due to customer (amount received - grand total).
     */
    #[Computed]
    public function balance(): float
    {
        $received = (float) ($this->amountReceived ?: 0);
        return $received - $this->grandTotal;
    }

    /**
     * Get products filtered by category and search term.
     */
    #[Computed]
    public function products()
    {
        $query = Product::with(['category', 'inventory'])
            ->where(function ($q) {
                // Products with inventory records that have stock
                $q->whereHas('inventory', function ($sub) {
                    $sub->where('quantity_in_stock', '>', 0);
                })
                // OR products without inventory records but with stock > 0
                ->orWhere(function ($sub) {
                    $sub->whereDoesntHave('inventory')
                        ->where('stock', '>', 0);
                });
            });

        if ($this->categoryFilter) {
            $query->where('category_id', $this->categoryFilter);
        }

        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('barcode', 'like', '%' . $this->search . '%');
            });
        }

        return $query->orderBy('name')->get();
    }

    /**
     * Get all categories.
     */
    #[Computed]
    public function categories()
    {
        return Category::orderBy('name')->get();
    }

    /**
     * Count items in cart.
     */
    #[Computed]
    public function cartCount(): int
    {
        return collect($this->cart)->sum('qty');
    }

    public function render()
    {
        return view('livewire.cashier-terminal')->layout('layouts.app');
    }
}
