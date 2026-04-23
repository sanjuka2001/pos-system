<?php

namespace App\Livewire\Admin;

use App\Models\Category;
use App\Models\Product;
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
    public bool $isEditing = false;
    public ?int $editingProductId = null;
    public ?int $deletingProductId = null;
    public string $deletingProductName = '';

    // Form fields
    #[Rule('required|string|max:255')]
    public string $name = '';

    #[Rule('required|string|max:50')]
    public string $barcode = '';

    #[Rule('required|numeric|min:0')]
    public string $price = '';

    #[Rule('required|integer|min:0')]
    public string $stock = '';

    #[Rule('required|exists:categories,id')]
    public string $category_id = '';

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
        $product = Product::findOrFail($productId);

        $this->resetForm();
        $this->isEditing = true;
        $this->editingProductId = $productId;
        $this->name = $product->name;
        $this->barcode = $product->barcode;
        $this->price = (string) $product->price;
        $this->stock = (string) $product->stock;
        $this->category_id = (string) $product->category_id;
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
            'barcode' => $this->barcode,
            'price' => (float) $this->price,
            'stock' => (int) $this->stock,
            'category_id' => (int) $this->category_id,
        ];

        if ($this->isEditing && $this->editingProductId) {
            Product::findOrFail($this->editingProductId)->update($data);
        } else {
            Product::create($data);
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
     * Quick stock update directly from the table.
     */
    public function updateStock(int $productId, int $newStock): void
    {
        if ($newStock >= 0) {
            Product::findOrFail($productId)->update(['stock' => $newStock]);
        }
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
        $this->barcode = '';
        $this->price = '';
        $this->stock = '';
        $this->category_id = '';
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
        return [
            'total' => Product::count(),
            'low_stock' => Product::where('stock', '>', 0)->where('stock', '<=', 10)->count(),
            'out_of_stock' => Product::where('stock', 0)->count(),
            'categories' => Category::count(),
        ];
    }

    /**
     * Get filtered, sorted, paginated products.
     */
    #[Computed]
    public function products()
    {
        $query = Product::with('category');

        // Search
        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('barcode', 'like', '%' . $this->search . '%');
            });
        }

        // Category filter
        if (!empty($this->categoryFilter)) {
            $query->where('category_id', $this->categoryFilter);
        }

        // Stock filter
        if ($this->stockFilter === 'low') {
            $query->where('stock', '>', 0)->where('stock', '<=', 10);
        } elseif ($this->stockFilter === 'out') {
            $query->where('stock', 0);
        }

        // Sort
        if ($this->sortField === 'category') {
            $query->join('categories', 'products.category_id', '=', 'categories.id')
                  ->orderBy('categories.name', $this->sortDirection)
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
