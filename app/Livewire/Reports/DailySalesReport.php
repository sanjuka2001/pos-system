<?php

namespace App\Livewire\Reports;

use App\Models\Order;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;

class DailySalesReport extends Component
{
    use WithPagination;

    public $startDate;
    public $endDate;
    public $sortDirection = 'desc';

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
        $this->resetPage();
    }

    public function toggleSort()
    {
        $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
    }

    public function getReportDataProperty()
    {
        $start = Carbon::parse($this->startDate)->startOfDay();
        $end = Carbon::parse($this->endDate)->endOfDay();

        return Order::where('status', 'completed')
            ->whereBetween('created_at', [$start, $end])
            ->selectRaw('DATE(created_at) as date, COUNT(*) as num_orders, SUM(grand_total) as total_amount, SUM(tax) as total_vat, SUM(grand_total) - SUM(tax) as net_amount')
            ->groupBy('date')
            ->orderBy('date', $this->sortDirection)
            ->get();
    }

    public function render()
    {
        return view('livewire.reports.daily-sales-report', [
            'reportData' => $this->reportData,
        ]);
    }
}
