<?php

namespace App\Livewire\Reports;

use App\Models\Order;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\Attributes\On;

class TaxReport extends Component
{
    public $startDate;
    public $endDate;
    public $groupBy = 'day';

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

    public function setGroupBy($group)
    {
        $this->groupBy = $group;
    }

    public function getReportDataProperty()
    {
        $start = Carbon::parse($this->startDate)->startOfDay();
        $end = Carbon::parse($this->endDate)->endOfDay();

        $groupExpr = match ($this->groupBy) {
            'day' => 'DATE(created_at)',
            'week' => 'YEARWEEK(created_at, 1)',
            'month' => 'DATE_FORMAT(created_at, "%Y-%m")',
            default => 'DATE(created_at)',
        };

        $formatExpr = match ($this->groupBy) {
            'day' => 'DATE(created_at) as period',
            'week' => 'CONCAT("Week ", WEEK(created_at, 1), ", ", YEAR(created_at)) as period',
            'month' => 'DATE_FORMAT(created_at, "%b %Y") as period',
            default => 'DATE(created_at) as period',
        };

        return Order::where('status', 'completed')
            ->whereBetween('created_at', [$start, $end])
            ->selectRaw("$formatExpr, SUM(grand_total) as total_sales, SUM(tax) as vat_collected")
            ->groupByRaw($groupExpr)
            ->orderByRaw("MIN(created_at)")
            ->get();
    }

    public function render()
    {
        return view('livewire.reports.tax-report', [
            'reportData' => $this->reportData,
        ]);
    }
}
