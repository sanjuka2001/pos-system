<?php

namespace App\Livewire\Reports;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;

class InventoryValueReport extends Component
{
    use WithPagination;

    public $totalInventoryValue = 0;

    public function mount()
    {
        $this->calculateTotal();
    }

    public function calculateTotal()
    {
        $this->totalInventoryValue = (float) Product::selectRaw('SUM(stock * cost_price) as total')
            ->value('total') ?? 0;
    }

    public function getReportDataProperty()
    {
        return Product::with('category')
            ->select('id', 'name', 'stock', 'price', 'cost_price', 'category_id')
            ->orderBy('name')
            ->get()
            ->map(function ($product) {
                $product->stock_value = (float) $product->stock * (float) $product->cost_price;
                $product->is_low_stock = $product->stock > 0 && $product->stock < 10;
                $product->is_out_of_stock = $product->stock <= 0;
                return $product;
            });
    }

    public function render()
    {
        return view('livewire.reports.inventory-value-report', [
            'reportData' => $this->reportData,
        ]);
    }
}
