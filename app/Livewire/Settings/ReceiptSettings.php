<?php

namespace App\Livewire\Settings;

use App\Models\Setting;
use Livewire\Component;

class ReceiptSettings extends Component
{
    public $receipt_header;
    public $receipt_footer;
    public $receipt_show_logo;
    public $receipt_show_tax;
    public $saved = false;

    // For preview
    public $store_name;
    public $tax_name;
    public $tax_rate;

    public function mount()
    {
        $this->receipt_header = Setting::get('receipt_header', '');
        $this->receipt_footer = Setting::get('receipt_footer', '');
        $this->receipt_show_logo = Setting::get('receipt_show_logo', 'true') === 'true';
        $this->receipt_show_tax = Setting::get('receipt_show_tax', 'true') === 'true';
        $this->store_name = Setting::get('store_name', 'My POS Store');
        $this->tax_name = Setting::get('tax_name', 'VAT');
        $this->tax_rate = Setting::get('tax_rate', '10');
    }

    public function saveReceiptSettings()
    {
        $this->validate([
            'receipt_header' => 'nullable|max:255',
            'receipt_footer' => 'nullable|max:255',
            'receipt_show_logo' => 'required|boolean',
            'receipt_show_tax' => 'required|boolean',
        ]);

        Setting::set('receipt_header', $this->receipt_header);
        Setting::set('receipt_footer', $this->receipt_footer);
        Setting::set('receipt_show_logo', $this->receipt_show_logo ? 'true' : 'false');
        Setting::set('receipt_show_tax', $this->receipt_show_tax ? 'true' : 'false');

        Setting::clearCache();
        $this->saved = true;
        $this->dispatch('settings-saved', message: 'Receipt settings saved successfully!');
    }

    public function render()
    {
        return view('livewire.settings.receipt-settings');
    }
}
