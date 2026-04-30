<?php

namespace App\Livewire\Reports;

use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\Attributes\On;

class CashierPerformanceReport extends Component
{
    public $startDate;
    public $endDate;

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

    public function getReportDataProperty()
    {
        $start = Carbon::parse($this->startDate)->startOfDay();
        $end = Carbon::parse($this->endDate)->endOfDay();

        return Order::where('status', 'completed')
            ->whereBetween('created_at', [$start, $end])
            ->whereNotNull('user_id')
            ->join('users', 'orders.user_id', '=', 'users.id')
            ->selectRaw('users.name as cashier_name, COUNT(*) as total_orders, SUM(orders.grand_total) as total_sales, AVG(orders.grand_total) as avg_order_value')
            ->groupBy('users.id', 'users.name')
            ->orderByDesc('total_sales')
            ->get();
    }

    public function render()
    {
        return view('livewire.reports.cashier-performance-report', [
            'reportData' => $this->reportData,
        ]);
    }
}
