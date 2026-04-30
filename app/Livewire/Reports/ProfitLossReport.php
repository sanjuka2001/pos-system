<?php

namespace App\Livewire\Reports;

use App\Models\Order;
use App\Models\OrderItem;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;

class ProfitLossReport extends Component
{
    use WithPagination;

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
        $this->resetPage();
        $this->loadChartData();
    }

    public function loadChartData()
    {
        $start = Carbon::parse($this->startDate)->startOfDay();
        $end = Carbon::parse($this->endDate)->endOfDay();

        $dailyData = Order::where('status', 'completed')
            ->whereBetween('created_at', [$start, $end])
            ->selectRaw('DATE(created_at) as date, COUNT(*) as total_orders, SUM(grand_total) as gross_sales, SUM(discount) as total_discount, SUM(tax) as vat_collected')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $labels = [];
        $salesData = [];
        $profitData = [];

        foreach ($dailyData as $day) {
            $labels[] = Carbon::parse($day->date)->format('M d');
            $salesData[] = round((float) $day->gross_sales, 2);

            $dayCogs = OrderItem::whereHas('order', function ($q) use ($day) {
                    $q->where('status', 'completed')
                      ->whereDate('created_at', $day->date);
                })
                ->join('products', 'order_items.product_id', '=', 'products.id')
                ->selectRaw('SUM(order_items.quantity * products.cost_price) as total_cost')
                ->value('total_cost') ?? 0;

            $profitData[] = round((float) $day->gross_sales - (float) $dayCogs, 2);
        }

        $this->chartData = [
            'labels' => $labels,
            'sales' => $salesData,
            'profit' => $profitData,
        ];
    }

    public function getReportDataProperty()
    {
        $start = Carbon::parse($this->startDate)->startOfDay();
        $end = Carbon::parse($this->endDate)->endOfDay();

        $rows = Order::where('status', 'completed')
            ->whereBetween('created_at', [$start, $end])
            ->selectRaw('DATE(created_at) as date, COUNT(*) as total_orders, SUM(grand_total) as gross_sales, SUM(discount) as total_discount, SUM(tax) as vat_collected')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->map(function ($row) {
                $cogs = OrderItem::whereHas('order', function ($q) use ($row) {
                        $q->where('status', 'completed')
                          ->whereDate('created_at', $row->date);
                    })
                    ->join('products', 'order_items.product_id', '=', 'products.id')
                    ->selectRaw('SUM(order_items.quantity * products.cost_price) as total_cost')
                    ->value('total_cost') ?? 0;

                $row->net_sales = (float) $row->gross_sales - (float) $row->total_discount;
                $row->net_profit = (float) $row->gross_sales - (float) $cogs;
                return $row;
            });

        return $rows;
    }

    public function render()
    {
        return view('livewire.reports.profit-loss-report', [
            'reportData' => $this->reportData,
        ]);
    }
}
