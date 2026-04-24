<?php

namespace App\Livewire\Admin;

use App\Models\Product;
use App\Models\StockMovement;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

class StockMovementHistory extends Component
{
    use WithPagination;

    public string $search = '';
    public string $typeFilter = '';
    public string $dateFrom = '';
    public string $dateTo = '';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedTypeFilter(): void
    {
        $this->resetPage();
    }

    public function updatedDateFrom(): void
    {
        $this->resetPage();
    }

    public function updatedDateTo(): void
    {
        $this->resetPage();
    }

    /**
     * Get filtered, paginated stock movements.
     */
    #[Computed]
    public function movements()
    {
        $query = StockMovement::with(['product', 'user'])
            ->latest();

        // Search by product name
        if (!empty($this->search)) {
            $query->whereHas('product', function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('barcode', 'like', '%' . $this->search . '%');
            });
        }

        // Filter by type
        if (!empty($this->typeFilter)) {
            $query->where('type', $this->typeFilter);
        }

        // Date range
        if (!empty($this->dateFrom)) {
            $query->whereDate('created_at', '>=', $this->dateFrom);
        }

        if (!empty($this->dateTo)) {
            $query->whereDate('created_at', '<=', $this->dateTo);
        }

        return $query->paginate(20);
    }

    /**
     * Get summary stats.
     */
    #[Computed]
    public function summary(): array
    {
        return [
            'total_movements' => StockMovement::count(),
            'sales' => StockMovement::where('type', 'sale')->count(),
            'restocks' => StockMovement::where('type', 'restock')->count(),
            'adjustments' => StockMovement::where('type', 'adjustment')->count(),
        ];
    }

    public function render()
    {
        $layout = auth()->user()?->role === 'manager' ? 'layouts.manager' : 'layouts.admin';
        return view('livewire.admin.stock-movement-history')->layout($layout);
    }
}
