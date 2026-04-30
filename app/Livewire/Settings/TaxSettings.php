<?php

namespace App\Livewire\Settings;

use App\Models\Setting;
use Livewire\Component;

class TaxSettings extends Component
{
    public $tax_enabled;
    public $tax_name;
    public $tax_rate;
    public $tax_number;
    public $prices_include_tax;
    public $saved = false;

    public function mount()
    {
        $this->tax_enabled = Setting::get('tax_enabled', 'true') === 'true';
        $this->tax_name = Setting::get('tax_name', 'VAT');
        $this->tax_rate = Setting::get('tax_rate', '10');
        $this->tax_number = Setting::get('tax_number', '');
        $this->prices_include_tax = Setting::get('prices_include_tax', 'false') === 'true';
    }

    public function saveTaxSettings()
    {
        $rules = [
            'tax_enabled' => 'required|boolean',
            'prices_include_tax' => 'required|boolean',
        ];

        if ($this->tax_enabled) {
            $rules['tax_name'] = 'required|max:50';
            $rules['tax_rate'] = 'required|numeric|min:0|max:100';
        } else {
            $rules['tax_name'] = 'nullable|max:50';
            $rules['tax_rate'] = 'nullable|numeric|min:0|max:100';
        }

        $rules['tax_number'] = 'nullable|max:100';

        $this->validate($rules);

        Setting::set('tax_enabled', $this->tax_enabled ? 'true' : 'false');
        Setting::set('tax_name', $this->tax_name);
        Setting::set('tax_rate', $this->tax_rate);
        Setting::set('tax_number', $this->tax_number);
        Setting::set('prices_include_tax', $this->prices_include_tax ? 'true' : 'false');

        Setting::clearCache();
        $this->saved = true;
        $this->dispatch('settings-saved', message: 'Tax settings saved successfully!');
    }

    public function getPreviewTaxProperty()
    {
        if (!$this->tax_enabled) return 0;
        return 100 * ((float) $this->tax_rate / 100);
    }

    public function getPreviewTotalProperty()
    {
        return 100 + $this->preview_tax;
    }

    public function render()
    {
        return view('livewire.settings.tax-settings');
    }
}
