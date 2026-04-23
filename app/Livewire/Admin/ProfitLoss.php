<?php

namespace App\Livewire\Admin;

use App\Models\Order;
use App\Models\OrderItem;
use Livewire\Component;

class ProfitLoss extends Component
{
    public $totalSales = 0;
    public $totalTax = 0;
    public $netProfit = 0;

    public function mount()
    {
        $this->calculateProfitLoss();
    }

    public function calculateProfitLoss()
    {
        $orders = Order::all();
        $this->totalSales = $orders->sum('total');
        $this->totalTax = $orders->sum('tax_vat') + $orders->sum('tax_sscl');
        
        // In a real system, you would subtract product cost (COGS) to get net profit.
        // For simplicity, we define net profit as Sales - Tax.
        $this->netProfit = $this->totalSales - $this->totalTax;
    }

    public function render()
    {
        return view('livewire.admin.profit-loss')->layout('layouts.admin');
    }
}
