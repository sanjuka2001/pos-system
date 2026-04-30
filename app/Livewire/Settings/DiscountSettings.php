<?php

namespace App\Livewire\Settings;

use App\Models\Setting;
use Livewire\Component;

class DiscountSettings extends Component
{
    public $discount_enabled;
    public $discount_type;
    public $discount_max_percentage;
    public $discount_max_fixed;
    public $discount_require_reason;
    public $saved = false;

    public function mount()
    {
        $this->discount_enabled = Setting::get('discount_enabled', 'true') === 'true';
        $this->discount_type = Setting::get('discount_type', 'percentage');
        $this->discount_max_percentage = Setting::get('discount_max_percentage', '10');
        $this->discount_max_fixed = Setting::get('discount_max_fixed', '500');
        $this->discount_require_reason = Setting::get('discount_require_reason', 'false') === 'true';
    }

    public function saveDiscountSettings()
    {
        $rules = [
            'discount_enabled' => 'required|boolean',
            'discount_type' => 'required|in:percentage,fixed',
            'discount_require_reason' => 'required|boolean',
        ];

        if ($this->discount_enabled) {
            $rules['discount_max_percentage'] = 'required|numeric|min:0|max:100';
            $rules['discount_max_fixed'] = 'required|numeric|min:0';
        }

        $this->validate($rules);

        Setting::set('discount_enabled', $this->discount_enabled ? 'true' : 'false');
        Setting::set('discount_type', $this->discount_type);
        Setting::set('discount_max_percentage', $this->discount_max_percentage);
        Setting::set('discount_max_fixed', $this->discount_max_fixed);
        Setting::set('discount_require_reason', $this->discount_require_reason ? 'true' : 'false');

        Setting::clearCache();
        $this->saved = true;
        $this->dispatch('settings-saved', message: 'Discount settings saved successfully!');
    }

    public function render()
    {
        return view('livewire.settings.discount-settings');
    }
}
