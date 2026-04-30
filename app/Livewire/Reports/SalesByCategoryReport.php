<?php

namespace App\Livewire\Reports;

use App\Models\OrderItem;
use App\Models\Order;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\Attributes\On;

class SalesByCategoryReport extends Component
{
    public $startDate;
    public $endDate;
    public $chartData = [];

    public function mount()
    {
        $this->startDate = now()->format('Y-m-d');
        $this->endDate = now()->format('Y-m-d');
        $this->loadChartData();
    }

    #[On('dateRangeChanged')]
    public function onDateRangeChanged($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->loadChartData();
    }

    public function loadChartData()
    {
        $data = $this->reportData;
        $this->chartData = [
            'labels' => $data->pluck('category_name')->toArray(),
            'values' => $data->pluck('total_revenue')->map(fn($v) => round((float)$v, 2))->toArray(),
        ];
    }

    public function getReportDataProperty()
    {
        $start = Carbon::parse($this->startDate)->startOfDay();
        $end = Carbon::parse($this->endDate)->endOfDay();

        $totalRevenue = OrderItem::whereHas('order', function ($q) use ($start, $end) {
                $q->where('status', 'completed')
                  ->whereBetween('created_at', [$start, $end]);
            })->sum('subtotal');

        return OrderItem::whereHas('order', function ($q) use ($start, $end) {
                $q->where('status', 'completed')
                  ->whereBetween('created_at', [$start, $end]);
            })
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->selectRaw('categories.name as category_name, COUNT(DISTINCT order_items.order_id) as total_orders, SUM(order_items.subtotal) as total_revenue')
            ->groupBy('categories.id', 'categories.name')
            ->orderByDesc('total_revenue')
            ->get()
            ->map(function ($row) use ($totalRevenue) {
                $row->percentage = $totalRevenue > 0 ? round(((float) $row->total_revenue / (float) $totalRevenue) * 100, 1) : 0;
                return $row;
            });
    }

    public function render()
    {
        return view('livewire.reports.sales-by-category-report', [
            'reportData' => $this->reportData,
        ]);
    }
}
