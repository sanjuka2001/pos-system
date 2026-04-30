<?php

namespace App\Livewire\Reports;

use App\Models\OrderItem;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\Attributes\On;

class TopProductsReport extends Component
{
    public $startDate;
    public $endDate;
    public $showAll = false;

    public function mount()
    {
        $this->startDate = now()->format('Y-m-d');
        $this->endDate = now()->format('Y-m-d');
    }

    #[On('dateRangeChanged')]
    public function onDateRangeChanged($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function toggleShowAll()
    {
        $this->showAll = !$this->showAll;
    }

    public function getReportDataProperty()
    {
        $start = Carbon::parse($this->startDate)->startOfDay();
        $end = Carbon::parse($this->endDate)->endOfDay();

        $query = OrderItem::whereHas('order', function ($q) use ($start, $end) {
                $q->where('status', 'completed')
                  ->whereBetween('created_at', [$start, $end]);
            })
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->selectRaw('products.name as product_name, categories.name as category_name, SUM(order_items.quantity) as total_qty, SUM(order_items.subtotal) as total_revenue, SUM(order_items.quantity * (products.price - products.cost_price)) as total_profit')
            ->groupBy('products.id', 'products.name', 'categories.name')
            ->orderByDesc('total_qty');

        if (!$this->showAll) {
            $query->limit(10);
        }

        return $query->get();
    }

    public function render()
    {
        return view('livewire.reports.top-products-report', [
            'reportData' => $this->reportData,
        ]);
    }
}
