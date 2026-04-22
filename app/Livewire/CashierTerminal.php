<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Product;
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

        $product = Product::where('barcode', trim($this->search))->first();

        if ($product) {
            $this->addToCart($product->id);
            $this->search = '';
            $this->dispatch('barcode-scanned');
        } else {
            // No exact match — try a name search and add first result
            $product = Product::where('name', 'like', '%' . trim($this->search) . '%')
                ->where('stock', '>', 0)
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
        $product = Product::find($productId);

        if (!$product || $product->stock <= 0) {
            return;
        }

        $key = (string) $productId;

        if (isset($this->cart[$key])) {
            if ($this->cart[$key]['qty'] < $product->stock) {
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
                'stock'      => $product->stock,
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
        $query = Product::with('category')->where('stock', '>', 0);

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
