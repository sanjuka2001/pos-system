<?php

namespace App\Livewire\Admin;

use App\Models\Category;
use App\Models\Inventory;
use App\Models\Product;
use App\Services\InventoryService;
use Barryvdh\DomPDF\Facade\Pdf;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class InventoryDashboard extends Component
{
    use WithPagination;

    // Search & Filter
    public string $search = '';
    public string $categoryFilter = '';
    public string $stockFilter = ''; // 'low', 'out', ''
    public string $sortField = 'name';
    public string $sortDirection = 'asc';

    // Modal state
    public bool $showModal = false;
    public bool $showDeleteModal = false;
    public bool $showRestockModal = false;
    public bool $isEditing = false;
    public ?int $editingProductId = null;
    public ?int $deletingProductId = null;
    public string $deletingProductName = '';

    // Restock form
    public ?int $restockProductId = null;
    public string $restockProductName = '';
    public string $restockQuantity = '';
    public string $restockNote = '';

    // Form fields
    #[Rule('required|string|max:255')]
    public string $name = '';

    #[Rule('required|string|max:50')]
    public string $barcode = '';

    public string $sku = '';

    #[Rule('required|numeric|min:0')]
    public string $price = '';

    public string $cost_price = '';

    #[Rule('required|integer|min:0')]
    public string $stock = '';

    #[Rule('required|exists:categories,id')]
    public string $category_id = '';

    public string $low_stock_alert = '10';

    /**
     * Reset pagination when search or filters change.
     */
    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedCategoryFilter(): void
    {
        $this->resetPage();
    }

    public function updatedStockFilter(): void
    {
        $this->resetPage();
    }

    /**
     * Sort by a given column.
     */
    public function sortBy(string $field): void
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    /**
     * Open the create product modal.
     */
    public function openCreateModal(): void
    {
        $this->resetForm();
        $this->isEditing = false;
        $this->showModal = true;
    }

    /**
     * Open the edit product modal.
     */
    public function openEditModal(int $productId): void
    {
        $product = Product::with('inventory')->findOrFail($productId);

        $this->resetForm();
        $this->isEditing = true;
        $this->editingProductId = $productId;
        $this->name = $product->name;
        $this->sku = $product->sku ?? '';
        $this->barcode = $product->barcode;
        $this->price = (string) $product->price;
        $this->cost_price = (string) ($product->cost_price ?? '');
        $this->stock = (string) ($product->inventory->quantity_in_stock ?? $product->stock);
        $this->category_id = (string) $product->category_id;
        $this->low_stock_alert = (string) ($product->inventory->low_stock_alert ?? 10);
        $this->showModal = true;
    }

    /**
     * Save the product (create or update).
     */
    public function saveProduct(): void
    {
        // Custom barcode uniqueness validation
        $barcodeRule = 'required|string|max:50|unique:products,barcode';
        if ($this->isEditing && $this->editingProductId) {
            $barcodeRule .= ',' . $this->editingProductId;
        }

        $this->validate([
            'name' => 'required|string|max:255',
            'barcode' => $barcodeRule,
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
        ]);

        $data = [
            'name' => $this->name,
            'sku' => $this->sku ?: null,
            'barcode' => $this->barcode,
            'price' => (float) $this->price,
            'cost_price' => $this->cost_price !== '' ? (float) $this->cost_price : null,
            'stock' => (int) $this->stock,
            'category_id' => (int) $this->category_id,
        ];

        $service = app(InventoryService::class);

        if ($this->isEditing && $this->editingProductId) {
            $product = Product::findOrFail($this->editingProductId);
            $product->update($data);

            // Update inventory record and log adjustment if stock changed
            $inventory = Inventory::firstOrCreate(
                ['product_id' => $product->id],
                ['quantity_in_stock' => (int) $this->stock, 'low_stock_alert' => (int) $this->low_stock_alert]
            );

            if ($inventory->quantity_in_stock !== (int) $this->stock) {
                $service->adjustStock(
                    $product->id,
                    (int) $this->stock,
                    auth()->id(),
                    'Stock adjusted via product edit'
                );
            }

            $inventory->update(['low_stock_alert' => (int) $this->low_stock_alert]);
        } else {
            $product = Product::create($data);

            // Create inventory record
            Inventory::create([
                'product_id' => $product->id,
                'quantity_in_stock' => (int) $this->stock,
                'low_stock_alert' => (int) $this->low_stock_alert,
            ]);
        }

        $this->closeModal();
    }

    /**
     * Open delete confirmation modal.
     */
    public function confirmDelete(int $productId): void
    {
        $product = Product::findOrFail($productId);
        $this->deletingProductId = $productId;
        $this->deletingProductName = $product->name;
        $this->showDeleteModal = true;
    }

    /**
     * Delete the product.
     */
    public function deleteProduct(): void
    {
        if ($this->deletingProductId) {
            Product::findOrFail($this->deletingProductId)->delete();
        }

        $this->showDeleteModal = false;
        $this->deletingProductId = null;
        $this->deletingProductName = '';
    }

    /**
     * Open restock modal for a product.
     */
    public function openRestockModal(int $productId): void
    {
        $product = Product::findOrFail($productId);
        $this->restockProductId = $productId;
        $this->restockProductName = $product->name;
        $this->restockQuantity = '';
        $this->restockNote = '';
        $this->showRestockModal = true;
    }

    /**
     * Close restock modal.
     */
    public function closeRestockModal(): void
    {
        $this->showRestockModal = false;
        $this->restockProductId = null;
        $this->restockProductName = '';
        $this->restockQuantity = '';
        $this->restockNote = '';
    }

    /**
     * Restock a product.
     */
    public function restockProduct(): void
    {
        $this->validate([
            'restockQuantity' => 'required|integer|min:1',
        ]);

        if ($this->restockProductId) {
            $service = app(InventoryService::class);
            $service->restockProduct(
                $this->restockProductId,
                (int) $this->restockQuantity,
                auth()->id(),
                $this->restockNote ?: null
            );
        }

        $this->closeRestockModal();
    }

    /**
     * Quick stock update directly from the table.
     */
    public function updateStock(int $productId, int $newStock): void
    {
        if ($newStock >= 0) {
            $service = app(InventoryService::class);
            $service->adjustStock(
                $productId,
                $newStock,
                auth()->id(),
                'Quick stock adjustment from inventory table'
            );
        }
    }

    /**
     * Export inventory to PDF.
     */
    public function exportPdf()
    {
        $products = Product::with(['category', 'inventory'])->orderBy('name')->get();

        $pdf = Pdf::loadView('exports.inventory-pdf', [
            'products' => $products,
            'generatedAt' => now()->format('Y-m-d H:i:s'),
        ]);

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, 'inventory-report-' . now()->format('Y-m-d') . '.pdf');
    }

    /**
     * Export inventory to Excel (CSV).
     */
    public function exportExcel()
    {
        $products = Product::with(['category', 'inventory'])->orderBy('name')->get();

        $csvContent = "Product Name,SKU,Barcode,Category,Price,Cost Price,Stock,Low Stock Alert,Status\n";

        foreach ($products as $product) {
            $stock = $product->inventory->quantity_in_stock ?? $product->stock;
            $alert = $product->inventory->low_stock_alert ?? 10;
            $status = $stock <= 0 ? 'Out of Stock' : ($stock <= $alert ? 'Low Stock' : 'In Stock');

            $csvContent .= implode(',', [
                '"' . str_replace('"', '""', $product->name) . '"',
                '"' . ($product->sku ?? '') . '"',
                '"' . $product->barcode . '"',
                '"' . ($product->category->name ?? '') . '"',
                $product->price,
                $product->cost_price ?? '',
                $stock,
                $alert,
                $status,
            ]) . "\n";
        }

        return response()->streamDownload(function () use ($csvContent) {
            echo $csvContent;
        }, 'inventory-report-' . now()->format('Y-m-d') . '.csv', [
            'Content-Type' => 'text/csv',
        ]);
    }

    /**
     * Close the create/edit modal and reset form.
     */
    public function closeModal(): void
    {
        $this->showModal = false;
        $this->resetForm();
    }

    /**
     * Close the delete modal.
     */
    public function closeDeleteModal(): void
    {
        $this->showDeleteModal = false;
        $this->deletingProductId = null;
        $this->deletingProductName = '';
    }

    /**
     * Reset form fields.
     */
    private function resetForm(): void
    {
        $this->resetValidation();
        $this->isEditing = false;
        $this->editingProductId = null;
        $this->name = '';
        $this->sku = '';
        $this->barcode = '';
        $this->price = '';
        $this->cost_price = '';
        $this->stock = '';
        $this->category_id = '';
        $this->low_stock_alert = '10';
    }

    /**
     * Get all categories for dropdowns.
     */
    #[Computed]
    public function categories()
    {
        return Category::orderBy('name')->get();
    }

    /**
     * Get product stats for dashboard cards.
     */
    #[Computed]
    public function stats(): array
    {
        $service = app(InventoryService::class);

        return [
            'total' => Product::count(),
            'low_stock' => $service->getLowStockCount(),
            'out_of_stock' => $service->getOutOfStockCount(),
            'categories' => Category::count(),
        ];
    }

    /**
     * Get filtered, sorted, paginated products.
     */
    #[Computed]
    public function products()
    {
        $query = Product::with(['category', 'inventory']);

        // Search
        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('barcode', 'like', '%' . $this->search . '%')
                  ->orWhere('sku', 'like', '%' . $this->search . '%');
            });
        }

        // Category filter
        if (!empty($this->categoryFilter)) {
            $query->where('category_id', $this->categoryFilter);
        }

        // Stock filter — uses inventory table
        if ($this->stockFilter === 'low') {
            $query->whereHas('inventory', function ($q) {
                $q->whereColumn('quantity_in_stock', '<=', 'low_stock_alert')
                  ->where('quantity_in_stock', '>', 0);
            });
        } elseif ($this->stockFilter === 'out') {
            $query->whereHas('inventory', function ($q) {
                $q->where('quantity_in_stock', '<=', 0);
            });
        }

        // Sort
        if ($this->sortField === 'category') {
            $query->join('categories', 'products.category_id', '=', 'categories.id')
                  ->orderBy('categories.name', $this->sortDirection)
                  ->select('products.*');
        } elseif ($this->sortField === 'stock') {
            $query->leftJoin('inventory', 'products.id', '=', 'inventory.product_id')
                  ->orderBy('inventory.quantity_in_stock', $this->sortDirection)
                  ->select('products.*');
        } else {
            $query->orderBy($this->sortField, $this->sortDirection);
        }

        return $query->paginate(12);
    }

    public function render()
    {
        $layout = auth()->user()?->role === 'manager' ? 'layouts.manager' : 'layouts.admin';
        return view('livewire.admin.inventory-dashboard')->layout($layout);
    }
}
