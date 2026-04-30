<?php

namespace App\Livewire\Reports;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Carbon\Carbon;
use Livewire\Component;

class ReportsDashboard extends Component
{
    public $activeTab = 'profit-loss';
    public $dateRange = 'today';
    public $startDate;
    public $endDate;

    // Summary card values
    public $totalSales = 0;
    public $totalTax = 0;
    public $netProfit = 0;
    public $totalOrders = 0;

    public function mount()
    {
        $this->startDate = now()->format('Y-m-d');
        $this->endDate = now()->format('Y-m-d');
        $this->calculateSummary();
    }

    public function setTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function setDateRange($range)
    {
        $this->dateRange = $range;
        $dates = $this->getDateBounds();
        $this->startDate = $dates[0]->format('Y-m-d');
        $this->endDate = $dates[1]->format('Y-m-d');
        $this->calculateSummary();
        $this->dispatch('dateRangeChanged', startDate: $this->startDate, endDate: $this->endDate);
    }

    public function applyFilter()
    {
        $this->dateRange = 'custom';
        $this->calculateSummary();
        $this->dispatch('dateRangeChanged', startDate: $this->startDate, endDate: $this->endDate);
    }

    public function getDateBounds()
    {
        return match ($this->dateRange) {
            'today' => [now()->startOfDay(), now()->endOfDay()],
            'this_week' => [now()->startOfWeek(), now()->endOfWeek()],
            'this_month' => [now()->startOfMonth(), now()->endOfMonth()],
            'this_year' => [now()->startOfYear(), now()->endOfYear()],
            'custom' => [
                Carbon::parse($this->startDate)->startOfDay(),
                Carbon::parse($this->endDate)->endOfDay(),
            ],
            default => [now()->startOfDay(), now()->endOfDay()],
        };
    }

    public function calculateSummary()
    {
        $dates = $this->getDateBounds();
        $start = $dates[0];
        $end = $dates[1];

        $orders = Order::where('status', 'completed')
            ->whereBetween('created_at', [$start, $end]);

        $this->totalSales = (float) $orders->sum('grand_total');
        $this->totalTax = (float) $orders->sum('tax');
        $this->totalOrders = (int) $orders->count();

        // Calculate cost of goods sold for net profit
        $cogs = OrderItem::whereHas('order', function ($q) use ($start, $end) {
                $q->where('status', 'completed')
                  ->whereBetween('created_at', [$start, $end]);
            })
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->selectRaw('SUM(order_items.quantity * products.cost_price) as total_cost')
            ->value('total_cost') ?? 0;

        $this->netProfit = $this->totalSales - (float) $cogs;
    }

    public function render()
    {
        $layout = auth()->user()->role === 'admin' ? 'layouts.admin' : 'layouts.manager';
        return view('livewire.reports.reports-dashboard')->layout($layout);
    }
}
