<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Product;
use App\Models\Setting;
use Livewire\Attributes\Computed;
use Livewire\Component;

class CashierTerminal extends Component
{
    public string $search = '';
    public ?int $categoryFilter = null;
    public array $cart = [];
    public string $amountReceived = '';
    public array $suggestions = [];
    public string $taxName = 'VAT';
    public float $taxRate = 0;
    public bool $taxEnabled = true;
    public bool $pricesIncludeTax = false;

    public function mount(): void
    {
        $this->taxEnabled = Setting::get('tax_enabled', 'true') === 'true';
        $this->taxName = Setting::get('tax_name', 'VAT') ?: 'VAT';
        $this->taxRate = (float) Setting::get('tax_rate', '0');
        $this->pricesIncludeTax = Setting::get('prices_include_tax', 'false') === 'true';
    }

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
            $this->suggestions = [];
            $this->dispatch('barcode-scanned');
        } else {
            // No exact match — try a name search and add first result
            $product = Product::where('name', 'like', '%' . trim($this->search) . '%')
                ->first();

            if ($product) {
                $this->addToCart($product->id);
                $this->search = '';
                $this->suggestions = [];
                $this->dispatch('barcode-scanned');
            }
        }
    }

    /**
     * Called from Alpine.js via $wire.getSuggestions(term).
     * Returns matching products from inventory for the dropdown.
     */
    public function getSuggestions(string $term): array
    {
        $term = trim($term);

        if (strlen($term) === 0) {
            return [];
        }

        return Product::where(function ($q) use ($term) {
                $q->where('name', 'like', '%' . $term . '%')
                  ->orWhere('barcode', 'like', '%' . $term . '%');
            })
            ->orderBy('name')
            ->take(8)
            ->get()
            ->map(fn ($p) => [
                'id'      => $p->id,
                'name'    => $p->name,
                'barcode' => $p->barcode,
                'price'   => (float) $p->price,
                'stock'   => $p->stock,
            ])
            ->toArray();
    }

    public function addFromSuggestion(int $productId): void
    {
        $this->addToCart($productId);
        $this->search = '';
        $this->suggestions = [];
        $this->dispatch('barcode-scanned');
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
     * Tax amount — dynamic rate from settings.
     *
     * If tax disabled: 0
     * If prices include tax: extract tax from subtotal
     * If prices exclude tax: add tax on top of subtotal
     */
    #[Computed]
    public function tax(): float
    {
        if (!$this->taxEnabled || $this->taxRate <= 0) {
            return 0;
        }

        $subtotal = $this->subtotal;

        if ($this->pricesIncludeTax) {
            // Tax is already embedded in the price — extract it
            return $subtotal * ($this->taxRate / (100 + $this->taxRate));
        }

        // Tax added on top
        return $subtotal * ($this->taxRate / 100);
    }

    /**
     * Grand total.
     *
     * If prices include tax: grand total = subtotal (tax already inside)
     * If prices exclude tax: grand total = subtotal + tax
     */
    #[Computed]
    public function grandTotal(): float
    {
        if ($this->pricesIncludeTax) {
            return $this->subtotal;
        }

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

    // searchSuggestions is now handled via updatedSearch() and the $suggestions public property

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
